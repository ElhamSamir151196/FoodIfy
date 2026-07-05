<?php

namespace App\Actions\DeliveryRider;

use App\Models\DeliveryRider;
use App\Repositories\DeliveryRiderRepository;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class CreateDeliveryRiderAction
{
    public function __construct(protected DeliveryRiderRepository $repository)
    {
    }

    public function execute(array $data): DeliveryRider
    {
        $data['password'] = Hash::make($data['password']);

        if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
            $data['avatar'] = $data['avatar']->store('riders/avatars', 'public');
        }

        return $this->repository->create($data);
    }
}
