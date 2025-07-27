<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SystemSetting;
use App\Rules\NotInPasswordHistory;
use App\Rules\PasswordComplexity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function resetPassword(Request $request, User $user)
    {
        // Récupérer la longueur minimale configurée
        $minLength = (int)SystemSetting::getValue(SystemSetting::PASSWORD_MIN_LENGTH, 8);

        // Valider la requête avec les règles configurées
        $request->validate([
            'new_password' => [
                'required',
                'string',
                "min:{$minLength}",
                new PasswordComplexity(),
                new NotInPasswordHistory($user)
            ],
        ], [
            'new_password.required' => 'Le mot de passe est requis.',
            'new_password.min' => "Le mot de passe doit contenir au moins {$minLength} caractères.",
        ]);

        // Sauvegarder l'ancien mot de passe dans l'historique
        $user->addToPasswordHistory($request->new_password);

        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->new_password);
        $user->password_changed_at = Carbon::now();
        $user->failed_login_attempts = 0;
        $user->locked_until = null;
        $user->save();

        return redirect()->back()->with('success', 'Mot de passe réinitialisé avec succès.');
    }

    public function toggleLock(User $user)
    {

        // Récupérer la longueur minimale configurée
        $maxAttempts = (int)SystemSetting::getValue(SystemSetting::MAX_LOGIN_ATTEMPTS, 3);

        if ($user->locked_until && $user->locked_until > now()) {
            // Déverrouiller
            $user->failed_login_attempts = 0;
            $user->locked_until = null;
            $message = 'Compte déverrouillé avec succès.';
        } else {
            // Verrouiller
            $user->failed_login_attempts = $maxAttempts;
            $user->locked_until = Carbon::now()->addMinutes(30);
            $message = 'Compte verrouillé avec succès.';
        }

        $user->save();
        return redirect()->back()->with('success', $message);
    }
}
