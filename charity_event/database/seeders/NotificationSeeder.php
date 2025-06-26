<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::all() as $user) {
            Notification::factory()->count(2)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
