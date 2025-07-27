<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\NotInPasswordHistory;
use App\Rules\PasswordComplexity;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PasswordChangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showChangeForm()
    {
        return view('auth.passwords.change');
    }

    public function change(Request $request)
    {
        $user = auth()->user();

        // Valider les données
        $request->validate([
            'new_password' => [
                'required',
                'string',
                'confirmed',
                'min:' . SystemSetting::getValue(SystemSetting::PASSWORD_MIN_LENGTH, 8),
                new PasswordComplexity(),
                new NotInPasswordHistory($user)
            ],
        ]);

        // Sauvegarder l'ancien mot de passe dans l'historique
        $user->addToPasswordHistory($request->new_password);

        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->new_password);
        $user->password_changed_at = Carbon::now();
        $user->force_password_change = false;
        $user->save();

        return redirect()->intended('/')
            ->with('success', 'Votre mot de passe a été changé avec succès.');
    }
}
