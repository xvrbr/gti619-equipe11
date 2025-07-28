<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ConfirmPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

     // Affiche le formulaire de confirmation du mot de passe
    public function showConfirmForm()
    {
        return view('auth.passwords.confirm');
    }

    // Vérifie le mot de passe soumis
    public function confirm(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Le mot de passe est incorrect.',
            ]);
        }

        // Marque la session comme récemment confirmée
        $request->session()->passwordConfirmed();

        // Redirige vers la page précédente ou d'accueil
        return redirect()->intended();
    }
}
