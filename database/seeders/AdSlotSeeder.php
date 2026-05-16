<?php

namespace Database\Seeders;

use App\Models\AdSlot;
use Illuminate\Database\Seeder;

class AdSlotSeeder extends Seeder
{
    public function run(): void
    {
        $slots = [
            'home_top', 'home_middle', 'article_top', 'article_middle', 'article_bottom',
            'tool_top', 'tool_middle', 'tool_bottom', 'template_top', 'template_middle',
            'template_bottom', 'sidebar',
        ];

        foreach ($slots as $slot) {
            AdSlot::updateOrCreate(
                ['key' => $slot],
                [
                    'name' => ucwords(str_replace('_', ' ', $slot)),
                    'location' => $slot,
                    'code' => null,
                    'is_active' => true,
                ],
            );
        }
    }
}
