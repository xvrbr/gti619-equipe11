<?php

namespace App\Rules;

use App\Models\SystemSetting;
use Illuminate\Contracts\Validation\Rule;

class PasswordComplexity implements Rule
{
    private $failedReason = '';

    public function passes($attribute, $value): bool
    {
        $hasUppercase = preg_match('/[A-Z]/', $value);
        $hasLowercase = preg_match('/[a-z]/', $value);
        $hasNumbers = preg_match('/[0-9]/', $value);
        $hasSpecial = preg_match('/[^A-Za-z0-9]/', $value);

        if (SystemSetting::getValue(SystemSetting::PASSWORD_REQUIRE_UPPERCASE, 'false') === 'true') {
            if (!$hasUppercase) {
                $this->failedReason = 'Le mot de passe doit contenir au moins une lettre majuscule.';
                return false;
            }
        }

        if (SystemSetting::getValue(SystemSetting::PASSWORD_REQUIRE_LOWERCASE, 'false') === 'true') {
            if (!$hasLowercase) {
                $this->failedReason = 'Le mot de passe doit contenir au moins une lettre minuscule.';
                return false;
            }
        }

        if (SystemSetting::getValue(SystemSetting::PASSWORD_REQUIRE_NUMBERS, 'false') === 'true') {
            if (!$hasNumbers) {
                $this->failedReason = 'Le mot de passe doit contenir au moins un chiffre.';
                return false;
            }
        }

        if (SystemSetting::getValue(SystemSetting::PASSWORD_REQUIRE_SPECIAL, 'false') === 'true') {
            if (!$hasSpecial) {
                $this->failedReason = 'Le mot de passe doit contenir au moins un caractère spécial.';
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        return $this->failedReason ?: 'Le mot de passe ne respecte pas les critères de complexité.';
    }
}
