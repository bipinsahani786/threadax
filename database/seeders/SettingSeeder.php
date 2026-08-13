<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'store_name', 'value' => 'ThreadAX'],
            ['key' => 'contact_email', 'value' => 'support@threadax.co.in'],
            ['key' => 'contact_phone', 'value' => '+91 98765 43210'],
            ['key' => 'free_shipping_threshold', 'value' => '999'],
            ['key' => 'flat_shipping_rate', 'value' => '79'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/threadax.co.in'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
