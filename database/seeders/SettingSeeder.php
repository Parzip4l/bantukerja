<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'BantuKerja.online', 'group' => 'site'],
            ['key' => 'site_tagline', 'value' => 'Tools dan template gratis untuk kerja, bisnis, dan administrasi harian.', 'group' => 'site'],
            ['key' => 'contact_email', 'value' => 'halo@bantukerja.online', 'group' => 'site'],
            ['key' => 'adsense_client_id', 'value' => '', 'group' => 'adsense'],
            ['key' => 'adsense_global_script', 'value' => '', 'group' => 'adsense'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting,
            );
        }
    }
}
