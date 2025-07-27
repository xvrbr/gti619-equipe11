<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        // Validation minimale : juste s'assurer que le champ n'est pas vide
        $request->validate([
            'new_password' => 'required|string',
        ], [
            'new_password.required' => 'Le mot de passe temporaire est requis.',
        ]);

        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->new_password);
        $user->password_changed_at = Carbon::now();
        $user->failed_login_attempts = 0;
        $user->locked_until = null;
        $user->force_password_change = true;  // Forcer le changement de mot de passe
        $user->save();

        return redirect()->back()->with('success', 'Mot de passe réinitialisé avec succès. L\'utilisateur devra changer son mot de passe à sa prochaine connexion.');
    }

    public function toggleLock(User $user)
    {
        if ($user->locked_until && $user->locked_until > now()) {
            // Déverrouiller
            $user->failed_login_attempts = 0;
            $user->locked_until = null;
            $message = 'Compte déverrouillé avec succès.';
        } else {
            // Verrouiller
            $user->failed_login_attempts = 3;
            $user->locked_until = Carbon::now()->addMinutes(30);
            $message = 'Compte verrouillé avec succès.';
        }

        $user->save();
        return redirect()->back()->with('success', $message);
    }
}
