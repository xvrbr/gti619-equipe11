<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    // Constantes pour les clés des paramètres
    const MAX_LOGIN_ATTEMPTS = 'max_login_attempts';
    const LOCK_DURATION_MINUTES = 'lock_duration_minutes';

    // Constantes pour les paramètres de mot de passe
    const PASSWORD_MIN_LENGTH = 'password_min_length';
    const PASSWORD_HISTORY_COUNT = 'password_history_count';
    const PASSWORD_REQUIRE_UPPERCASE = 'password_require_uppercase';
    const PASSWORD_REQUIRE_LOWERCASE = 'password_require_lowercase';
    const PASSWORD_REQUIRE_NUMBERS = 'password_require_numbers';
    const PASSWORD_REQUIRE_SPECIAL = 'password_require_special';
    const PASSWORD_MIN_CHARACTER_CLASSES = 'password_min_character_classes';

    /**
     * Récupère la valeur d'un paramètre
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Définit la valeur d'un paramètre
     */
    public static function setValue(string $key, string $value, string $description = null)
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->value = $value;
        if ($description) {
            $setting->description = $description;
        }
        $setting->save();
        return $setting;
    }

    /**
     * Récupère les règles de validation pour le mot de passe
     */
    public static function getPasswordRules(): array
    {
        $rules = ['required'];

        // Longueur minimale
        $minLength = (int)static::getValue(static::PASSWORD_MIN_LENGTH, 8);
        $rules[] = "min:{$minLength}";

        // Classes de caractères requises
        if (static::getValue(static::PASSWORD_REQUIRE_UPPERCASE, 'false') === 'true') {
            $rules[] = 'regex:/[A-Z]/';
        }
        if (static::getValue(static::PASSWORD_REQUIRE_LOWERCASE, 'false') === 'true') {
            $rules[] = 'regex:/[a-z]/';
        }
        if (static::getValue(static::PASSWORD_REQUIRE_NUMBERS, 'false') === 'true') {
            $rules[] = 'regex:/[0-9]/';
        }
        if (static::getValue(static::PASSWORD_REQUIRE_SPECIAL, 'false') === 'true') {
            $rules[] = 'regex:/[^A-Za-z0-9]/';
        }

        return $rules;
    }
}
