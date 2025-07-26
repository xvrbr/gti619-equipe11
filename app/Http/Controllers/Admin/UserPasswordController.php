<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserPasswordController extends Controller
{
    public function index()
    {
        $users = User::all();
        $settings = [
            'max_login_attempts' => SystemSetting::getValue(SystemSetting::MAX_LOGIN_ATTEMPTS, 3),
            'lock_duration_minutes' => SystemSetting::getValue(SystemSetting::LOCK_DURATION_MINUTES, 30),
        ];

        return view('admin.users.password-management', compact('users', 'settings'));
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|min:8',
        ]);

        $user->password = Hash::make($request->new_password);
        $user->password_changed_at = Carbon::now();
        $user->failed_login_attempts = 0;
        $user->locked_until = null;
        $user->save();

        return redirect()->back()->with('success', 'Mot de passe réinitialisé avec succès.');
    }

    public function unlockAccount(User $user)
    {
        $user->failed_login_attempts = 0;
        $user->locked_until = null;
        $user->save();

        return redirect()->back()->with('success', 'Compte déverrouillé avec succès.');
    }

    public function updateSettings(Request $request)
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

        return redirect()->back()->with('success', 'Paramètres mis à jour avec succès.');
    }
}
