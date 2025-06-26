<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'message' => $this->faker->sentence(),
            'seen' => $this->faker->boolean(30),
            'created_at' => now(),
        ];
    }
}
