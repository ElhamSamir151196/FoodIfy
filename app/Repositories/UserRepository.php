<?php

namespace App\Repositories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByPhone(string $phone): ?User
    {
        return User::where('phone', $phone)->first();
    }

    public function updatePassword(string $phone, string $hashedPassword): void
    {
        User::where('phone', $phone)
            ->update(['password' => $hashedPassword]);
    }

    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);

        return $user->fresh();
    }

    public function updateAvatar(User $user, string $path): User
    {
        $user->update(['avatar' => $path]);

        return $user->fresh();
    }

    public function updatePasswordFor(User $user, string $hashedPassword): void
    {
        $user->update(['password' => $hashedPassword]);
    }

    // ── Admin ─────────────────────────────
    public function getAllFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return User::query()
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($filters['role'] ?? null, fn ($q, $role) => $q->where('role', $role))
            ->latest()
            ->paginate($perPage);
    }

    public function findWithOrders(int $id): ?User
    {
        return User::with(['orders' => fn ($q) => $q->latest()->limit(20)])->find($id);
    }

    public function toggleStatus(User $user): User
    {
        $user->update(['is_active' => !$user->is_active]);

        return $user->fresh();
    }

    // ── Stats ──────────────────────────────
    public function countTotal(): int
    {
        return User::customers()->count();
    }

    public function countNewToday(): int
    {
        return User::customers()->whereDate('created_at', today())->count();
    }
}