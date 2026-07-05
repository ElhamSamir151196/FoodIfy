<?php

namespace App\Actions\DeliveryRider;

use App\Models\DeliveryRider;
use App\Repositories\DeliveryRiderRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UpdateDeliveryRiderAction
{
    public function __construct(protected DeliveryRiderRepository $repository)
    {
    }

    public function execute(DeliveryRider $rider, array $data): DeliveryRider
    {
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
            if ($rider->avatar) {
                Storage::disk('public')->delete($rider->avatar);
            }
            $data['avatar'] = $data['avatar']->store('riders/avatars', 'public');
        }

        return $this->repository->update($rider, $data);
    }
}
