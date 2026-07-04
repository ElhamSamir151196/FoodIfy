<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentTransactionRepository;
use Illuminate\Support\Facades\Http;

class PaymobService
{
    private const BASE_URL = 'https://accept.paymob.com/api';

    private ?string $authToken = null;

    public function __construct(
        private readonly PaymentTransactionRepository $transactions,
        private readonly OrderRepository               $orders,
    ) {}

    // ── Authenticate ──────────────────────
    public function authenticate(): string
    {
        $response = Http::post(self::BASE_URL . '/auth/tokens', [
            'api_key' => config('services.paymob.api_key'),
        ])->throw();

        $this->authToken = $response->json('token');

        return $this->authToken;
    }

    // ── Create Order on Paymob ────────────
    public function createOrder(Order $order): int
    {
        $response = Http::post(self::BASE_URL . '/ecommerce/orders', [
            'auth_token'       => $this->authToken,
            'delivery_needed'  => false,
            'amount_cents'     => (int) round($order->total * 100),
            'currency'         => 'EGP',
            'merchant_order_id'=> $order->id,
            'items'            => [],
        ])->throw();

        return (int) $response->json('id');
    }

    // ── Create Payment Key ────────────────
    public function createPaymentKey(string $authToken, int $paymobOrderId, Order $order): string
    {
        $order->loadMissing('user');

        $response = Http::post(self::BASE_URL . '/acceptance/payment_keys', [
            'auth_token'     => $authToken,
            'amount_cents'   => (int) round($order->total * 100),
            'expiration'     => 3600,
            'order_id'       => $paymobOrderId,
            'currency'       => 'EGP',
            'integration_id' => config('services.paymob.integration_id'),
            'billing_data'   => [
                'first_name' => $order->user->name ?? 'N/A',
                'last_name'  => 'N/A',
                'phone_number' => $order->user->phone ?? 'N/A',
                'email'      => $order->user->email ?? 'na@foodify.test',
                'apartment'  => 'N/A',
                'floor'      => 'N/A',
                'street'     => $order->delivery_address ?? 'N/A',
                'building'   => 'N/A',
                'city'       => 'N/A',
                'country'    => 'EG',
                'state'      => 'N/A',
            ],
        ])->throw();

        return $response->json('token');
    }

    // ── Iframe URL helper ─────────────────
    public function iframeUrl(string $paymentKey): string
    {
        return sprintf(
            'https://accept.paymob.com/api/acceptance/iframes/%s?payment_token=%s',
            config('services.paymob.iframe_id'),
            $paymentKey
        );
    }

    // ── Handle Callback ────────────────────
    public function handleCallback(array $data): ?PaymentTransaction
    {
        if (!$this->verifyHmac($data)) {
            return null;
        }

        $obj = $data['obj'] ?? $data;

        $transaction = $this->transactions->findByPaymobOrderId((string) ($obj['order']['id'] ?? ''));

        if (!$transaction) {
            return null;
        }

        $success = (bool) ($obj['success'] ?? false);

        $this->transactions->updateStatus(
            $transaction,
            $success ? \App\Enums\PaymentStatus::Paid : \App\Enums\PaymentStatus::Failed,
            (string) ($obj['id'] ?? ''),
            $obj
        );

        if ($success) {
            $this->orders->markAsPaid($transaction->order_id);
        }

        return $transaction->fresh();
    }

    // ── HMAC Verification ─────────────────
    private function verifyHmac(array $data): bool
    {
        $obj = $data['obj'] ?? $data;
        $hmacReceived = $data['hmac'] ?? null;

        if (!$hmacReceived) {
            return false;
        }

        $orderedFields = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auction',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order.id',
            'owner',
            'pending',
            'source_data.pan',
            'source_data.sub_type',
            'source_data.type',
            'success',
        ];

        $concatenated = '';

        foreach ($orderedFields as $field) {
            $value = data_get($obj, $field);
            $concatenated .= is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
        }

        $calculatedHmac = hash_hmac('sha512', $concatenated, config('services.paymob.hmac_secret'));

        return hash_equals($calculatedHmac, $hmacReceived);
    }
}