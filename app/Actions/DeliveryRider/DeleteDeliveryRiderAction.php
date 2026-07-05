<?php

namespace App\Actions\DeliveryRider;

use App\Models\DeliveryRider;
use App\Repositories\DeliveryRiderRepository;
use Illuminate\Support\Facades\Storage;

class DeleteDeliveryRiderAction
{
    public function __construct(protected DeliveryRiderRepository $repository)
    {
    }

    public function execute(DeliveryRider $rider): bool
    {
        if ($rider->avatar) {
            Storage::disk('public')->delete($rider->avatar);
        }

        return $this->repository->delete($rider);
    }
}
