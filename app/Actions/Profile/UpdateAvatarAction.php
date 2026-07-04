<?php

namespace App\Actions\Profile;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateAvatarAction
{
    public function __construct(private readonly UserRepository $users) {}

    public function execute(User $user, UploadedFile $avatar): User
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $avatar->store('avatars', 'public');

        return $this->users->updateAvatar($user, $path);
    }
}