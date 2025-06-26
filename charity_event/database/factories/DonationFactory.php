<?php

namespace Database\Factories;

use App\Models\Donation;
use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationFactory extends Factory
{
    protected $model = Donation::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::inRandomOrder()->first()?->id,
            'donor_id' => User::where('role', 'donor')->inRandomOrder()->first()?->id,
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'donated_at' => now(),
        ];
    }
}
