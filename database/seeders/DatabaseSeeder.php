<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            GeneratorTemplateSeeder::class,
            ToolSeeder::class,
            DocumentTemplateSeeder::class,
            PostSeeder::class,
            PageSeeder::class,
            AdSlotSeeder::class,
            SettingSeeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'admin@bantukerja.online'],
            [
                'name' => 'Admin Bantukerja',
                'role' => 'admin',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );
    }
}
