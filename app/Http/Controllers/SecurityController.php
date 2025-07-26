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
        ];

        return view('admin.security.index', compact('settings'));
    }

    public function update(Request $request)
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

        return redirect()->back()->with('success', 'Paramètres de sécurité mis à jour avec succès.');
    }
}
