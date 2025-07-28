<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityLog extends Model
{
    protected $fillable = [
        'user_id',
        'event_type',
        'username',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Types d'événements constants
    public const LOGIN_SUCCESS = 'login_success';
    public const LOGIN_FAILURE = 'login_failure';
    public const PASSWORD_CHANGE = 'password_change';

    // Méthodes utilitaires pour créer des logs
    public static function logLoginSuccess($user)
    {
        return self::create([
            'user_id' => $user->id,
            'username' => $user->username,
            'event_type' => self::LOGIN_SUCCESS,
            'description' => 'Connexion réussie'
        ]);
    }

    public static function logLoginFailure($username)
    {
        return self::create([
            'username' => $username,
            'event_type' => self::LOGIN_FAILURE,
            'description' => 'Tentative de connexion échouée'
        ]);
    }

    public static function logPasswordChange($user)
    {
        return self::create([
            'user_id' => $user->id,
            'username' => $user->username,
            'event_type' => self::PASSWORD_CHANGE,
            'description' => 'Changement de mot de passe effectué'
        ]);
    }
}
