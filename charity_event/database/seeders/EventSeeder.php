<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo 5 tổ chức
        $organizations = User::factory()->count(5)->create([
            'role' => 'organization',
        ]);

        // Mỗi tổ chức tạo 2 sự kiện
        foreach ($organizations as $org) {
            Event::factory()->count(2)->make()->each(function ($event) use ($org) {
                $event->user_id = $org->id;
                $event->organizer_name = $org->organization_name ?? 'Tổ chức không rõ';
                $event->phone = $org->phone ?? '0000000000';
                $event->bank_account = $org->bank_account ?? '123456789';
                $event->bank_name = $org->bank_name ?? 'Ngân hàng mặc định';
                $event->save();
            });
        }
    }
}
