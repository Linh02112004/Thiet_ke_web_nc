<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Event;
use App\Models\User;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach (Event::all() as $event) {
            Comment::factory()->count(3)->create([
                'event_id' => $event->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
