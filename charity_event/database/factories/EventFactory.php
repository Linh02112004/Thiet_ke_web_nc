<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

public function definition(): array
{
    $organization = User::where('role', 'organization')->inRandomOrder()->first();

    return [
        'user_id' => $organization ? $organization->id : null,
        'event_name' => $this->faker->sentence(3),
        'description' => $this->faker->paragraph(),
        'location' => $this->faker->city(),
        'goal' => $this->faker->randomFloat(2, 1000, 100000),
        'organizer_name' => $organization->organization_name ?? 'Tổ chức ẩn danh',
        'phone' => $organization->phone ?? '0000000000',
        'bank_account' => $organization->bank_account ?? '999999999',
        'bank_name' => $organization->bank_name ?? 'Ngân hàng Không xác định',
        'status' => 'ongoing',
        'created_at' => now(),
        'updated_at' => now(),
    ];
}
}
