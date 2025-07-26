<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    // Constantes pour les clés des paramètres
    const MAX_LOGIN_ATTEMPTS = 'max_login_attempts';
    const LOCK_DURATION_MINUTES = 'lock_duration_minutes';

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
}
