<?php

namespace App\Rules;

use App\Models\SystemSetting;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class NotInPasswordHistory implements Rule
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function passes($attribute, $value): bool
    {
        $historyCount = (int)SystemSetting::getValue(SystemSetting::PASSWORD_HISTORY_COUNT, 3);

        // Récupérer l'historique des mots de passe
        $passwordHistory = $this->user->passwordHistory()
            ->latest()
            ->take($historyCount)
            ->get();

        // Vérifier si le nouveau mot de passe existe dans l'historique
        foreach ($passwordHistory as $history) {
            if (Hash::check($value, $history->password)) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        $historyCount = SystemSetting::getValue(SystemSetting::PASSWORD_HISTORY_COUNT, 3);
        return "Le mot de passe ne peut pas être l'un de vos {$historyCount} derniers mots de passe.";
    }
}
