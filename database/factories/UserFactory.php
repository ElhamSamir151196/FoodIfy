<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    // ─────────────────────────────────────────
    // Default State → Client
    // ─────────────────────────────────────────
    public function definition(): array
    {
        return [
            'full_name'         => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'password'          => Hash::make('password'),
            'phone'             => fake()->phoneNumber(),
            'avatar'            => null,
            'role'              => UserRole::Client,
            'address'           => fake()->address(),
            'is_active'         => true,
            'phone_verified_at' => now(),
            'remember_token'    => Str::random(10),
        ];
    }

    // ─────────────────────────────────────────
    // States
    // ─────────────────────────────────────────
    public function admin(): static
    {
        return $this->state(fn () => [
            'role' => UserRole::Admin,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'is_active' => false,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
        ]);
    }
}