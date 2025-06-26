<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $role = $this->faker->randomElement(['donor', 'organization']);

        return [
            'id' => (string) Str::uuid(),
            'full_name' => $role === 'donor' ? $this->faker->name() : null,
            'organization_name' => $role === 'organization' ? $this->faker->company() : null,
            'description' => $this->faker->optional()->sentence(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $role === 'donor' ? $this->faker->unique()->phoneNumber() : null,
            'address' => $this->faker->optional()->address(),
            'password_hash' => Hash::make('password'),
            'website' => $this->faker->optional()->url(),
            'social_media' => $this->faker->optional()->url(),
            'role' => $role,
            'created_at' => now(),
        ];
    }
}
