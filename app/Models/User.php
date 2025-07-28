<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'locked_until' => 'datetime',
    ];

    /**
     * Relation avec l'historique des mots de passe
     */
    public function passwordHistory()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    /**
     * Ajoute un mot de passe à l'historique
     */
    public function addToPasswordHistory(string $password)
    {
        $this->passwordHistory()->create([
            'password' => Hash::make($password)
        ]);

        // Supprimer les anciens mots de passe qui dépassent la limite
        $historyCount = (int)SystemSetting::getValue(SystemSetting::PASSWORD_HISTORY_COUNT, 3);
        $oldPasswords = $this->passwordHistory()
            ->latest()
            ->skip($historyCount)
            ->take(100)  // Limite de sécurité
            ->get();

        foreach ($oldPasswords as $old) {
            $old->delete();
        }
    }
}
