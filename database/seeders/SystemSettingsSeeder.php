<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'key' => SystemSetting::MAX_LOGIN_ATTEMPTS,
                'value' => '3',
                'description' => 'Nombre maximum de tentatives de connexion avant verrouillage'
            ],
            [
                'key' => SystemSetting::LOCK_DURATION_MINUTES,
                'value' => '30',
                'description' => 'Durée de verrouillage du compte en minutes'
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
