<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
{
    $role = $this->attributes['role'] ?? $this->faker->randomElement(['donor', 'organization']);

    $password = match ($role) {
        'donor' => 'donor123',
        'organization' => 'org123',
        default => 'password',
    };

    return [
        'id' => (string) Str::uuid(),
        'full_name' => $role === 'donor' ? $this->faker->name() : null,
        'organization_name' => $role === 'organization' ? $this->faker->company() : null,
        'description' => $this->faker->optional()->sentence(),
        'email' => $this->faker->unique()->safeEmail(),
        'phone' => $this->faker->phoneNumber(),
        'address' => $this->faker->address(),
        'password_hash' => Hash::make($password),
        'website' => $role === 'organization' ? $this->faker->url() : null,
        'social_media' => $role === 'organization' ? $this->faker->url() : null,
        'role' => $role,
        'created_at' => now(),
    ];
}

}
