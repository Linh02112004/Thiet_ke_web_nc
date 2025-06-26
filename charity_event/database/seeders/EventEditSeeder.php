<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventEdit;
use App\Models\Event;

class EventEditSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Event::all()->take(5) as $event) {
            EventEdit::factory()->create([
                'event_id' => $event->id,
                'user_id' => $event->user_id,
            ]);
        }
    }
}
