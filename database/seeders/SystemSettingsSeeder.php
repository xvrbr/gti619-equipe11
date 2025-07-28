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
            // Paramètres de mot de passe
            [
                'key' => SystemSetting::PASSWORD_MIN_LENGTH,
                'value' => '8',
                'description' => 'Longueur minimale du mot de passe'
            ],
            [
                'key' => SystemSetting::PASSWORD_HISTORY_COUNT,
                'value' => '3',
                'description' => 'Nombre de mots de passe à mémoriser'
            ],
            [
                'key' => SystemSetting::PASSWORD_REQUIRE_UPPERCASE,
                'value' => 'false',
                'description' => 'Exiger des lettres majuscules'
            ],
            [
                'key' => SystemSetting::PASSWORD_REQUIRE_LOWERCASE,
                'value' => 'false',
                'description' => 'Exiger des lettres minuscules'
            ],
            [
                'key' => SystemSetting::PASSWORD_REQUIRE_NUMBERS,
                'value' => 'false',
                'description' => 'Exiger des chiffres'
            ],
            [
                'key' => SystemSetting::PASSWORD_REQUIRE_SPECIAL,
                'value' => 'false',
                'description' => 'Exiger des caractères spéciaux'
            ],
            [
                'key' => SystemSetting::PASSWORD_EXPIRATION_DAYS,
                'value' => '90',
                'description' => 'Nombre de jours avant expiration du mot de passe (0 pour désactiver)'
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
