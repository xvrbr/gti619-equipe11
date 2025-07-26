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
