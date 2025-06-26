<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Admin cố định
        User::create([
            'id' => (string) Str::uuid(),
            'full_name' => 'Admin',
            'organization_name' => null,
            'description' => 'Super Admin',
            'email' => 'admin@example.com',
            'phone' => null,
            'address' => 'Admin City',
            'password_hash' => Hash::make('admin123'), // mật khẩu đăng nhập
            'website' => null,
            'social_media' => null,
            'role' => 'admin',
            'created_at' => now(),
        ]);

        // 2️⃣ 5 donor
        User::factory()->count(5)->create(['role' => 'donor']);

        // 3️⃣ 5 organization
        User::factory()->count(5)->create(['role' => 'organization']);
    }
}
