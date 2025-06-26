<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\Event;
use App\Models\User;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $donors = User::where('role', 'donor')->get();
        $events = Event::all();

        foreach ($donors as $donor) {
            Donation::factory()->count(2)->create([
                'donor_id' => $donor->id,
                'event_id' => $events->random()->id,
            ]);
        }
    }
}
