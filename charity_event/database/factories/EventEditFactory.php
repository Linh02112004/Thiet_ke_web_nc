<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventEditFactory extends Factory
{
    public function definition(): array
    {
        $event = Event::inRandomOrder()->first();
        return [
            'event_id' => $event->id,
            'user_id' => $event->user_id,
            'event_name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->city(),
            'organizer_name' => $this->faker->company(),
            'phone' => $this->faker->phoneNumber(),
            'goal' => $this->faker->randomFloat(2, 1000, 50000),
            'status' => 'pending',
            'reason' => null,
            'created_at' => now(),
        ];
    }
}
