<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:administrateur');
    }

    public function index()
    {
        $settings = [
            'max_login_attempts' => SystemSetting::getValue(SystemSetting::MAX_LOGIN_ATTEMPTS, 3),
            'lock_duration_minutes' => SystemSetting::getValue(SystemSetting::LOCK_DURATION_MINUTES, 30),
            'password_min_length' => SystemSetting::getValue(SystemSetting::PASSWORD_MIN_LENGTH, 8),
            'password_history_count' => SystemSetting::getValue(SystemSetting::PASSWORD_HISTORY_COUNT, 3),
            'password_require_uppercase' => SystemSetting::getValue(SystemSetting::PASSWORD_REQUIRE_UPPERCASE, 'false'),
            'password_require_lowercase' => SystemSetting::getValue(SystemSetting::PASSWORD_REQUIRE_LOWERCASE, 'false'),
            'password_require_numbers' => SystemSetting::getValue(SystemSetting::PASSWORD_REQUIRE_NUMBERS, 'false'),
            'password_require_special' => SystemSetting::getValue(SystemSetting::PASSWORD_REQUIRE_SPECIAL, 'false'),
            'password_expiration_days' => SystemSetting::getValue(SystemSetting::PASSWORD_EXPIRATION_DAYS, 90),
        ];

        return view('admin.security.index', compact('settings'));
    }

    public function updateLoginSettings(Request $request)
    {
        $request->validate([
            'max_login_attempts' => 'required|integer|min:1',
            'lock_duration_minutes' => 'required|integer|min:1',
        ]);

        SystemSetting::setValue(
            SystemSetting::MAX_LOGIN_ATTEMPTS,
            $request->max_login_attempts,
            'Nombre maximum de tentatives de connexion avant verrouillage'
        );

        SystemSetting::setValue(
            SystemSetting::LOCK_DURATION_MINUTES,
            $request->lock_duration_minutes,
            'Durée de verrouillage du compte en minutes'
        );

        return redirect()->back()->with('success', 'Paramètres de connexion mis à jour avec succès.');
    }

    public function updatePasswordSettings(Request $request)
    {
        $request->validate([
            'password_min_length' => 'required|integer|min:1',
            'password_history_count' => 'required|integer|min:1',
            'password_expiration_days' => 'required|integer|min:0',
        ]);

        SystemSetting::setValue(
            SystemSetting::PASSWORD_MIN_LENGTH,
            $request->password_min_length,
            'Longueur minimale du mot de passe'
        );

        SystemSetting::setValue(
            SystemSetting::PASSWORD_HISTORY_COUNT,
            $request->password_history_count,
            'Nombre de mots de passe à mémoriser'
        );

        SystemSetting::setValue(
            SystemSetting::PASSWORD_EXPIRATION_DAYS,
            $request->password_expiration_days,
            'Nombre de jours avant expiration du mot de passe'
        );

        SystemSetting::setValue(
            SystemSetting::PASSWORD_REQUIRE_UPPERCASE,
            $request->has('password_require_uppercase') ? 'true' : 'false',
            'Exiger des lettres majuscules'
        );

        SystemSetting::setValue(
            SystemSetting::PASSWORD_REQUIRE_LOWERCASE,
            $request->has('password_require_lowercase') ? 'true' : 'false',
            'Exiger des lettres minuscules'
        );

        SystemSetting::setValue(
            SystemSetting::PASSWORD_REQUIRE_NUMBERS,
            $request->has('password_require_numbers') ? 'true' : 'false',
            'Exiger des chiffres'
        );

        SystemSetting::setValue(
            SystemSetting::PASSWORD_REQUIRE_SPECIAL,
            $request->has('password_require_special') ? 'true' : 'false',
            'Exiger des caractères spéciaux'
        );

        return redirect()->back()->with('success', 'Politique de mot de passe mise à jour avec succès.');
    }
}
