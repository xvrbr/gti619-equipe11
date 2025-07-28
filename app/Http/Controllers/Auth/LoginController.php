<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\SecurityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Vérifier si l'utilisateur existe
        $user = User::where('username', $credentials['username'])->first();

        if ($user) {
            // Vérifier si le compte est verrouillé
            if ($user->locked_until && $user->locked_until > now()) {
                $lockRemaining = Carbon::now()->diffInMinutes($user->locked_until);
                throw ValidationException::withMessages([
                    'username' => ["Ce compte est verrouillé. Réessayez dans {$lockRemaining} minutes."],
                ]);
            }

            // Tentative de connexion
            if (Auth::attempt($credentials)) {
                // Réinitialiser les tentatives en cas de succès
                $user->failed_login_attempts = 0;
                $user->locked_until = null;
                $user->save();

                SecurityLog::logLoginSuccess($user);

                $request->session()->regenerate();

                // Redirection basée sur le rôle
                switch($user->role) {
                    case 'administrateur':
                        return redirect()->route('admin.security');
                    case 'prepose_residentiel':
                        return redirect()->route('clients.residential');
                    case 'prepose_affaire':
                        return redirect()->route('clients.business');
                    default:
                        return redirect('/');
                }
            }

            // Gérer les tentatives échouées
            $maxAttempts = (int)SystemSetting::getValue(SystemSetting::MAX_LOGIN_ATTEMPTS, 3);
            $lockDuration = (int)SystemSetting::getValue(SystemSetting::LOCK_DURATION_MINUTES, 30);

            $user->failed_login_attempts++;

            if ($user->failed_login_attempts >= $maxAttempts) {
                $user->locked_until = Carbon::now()->addMinutes($lockDuration);
                $message = "Compte verrouillé pour {$lockDuration} minutes après {$maxAttempts} tentatives échouées.";
            } else {
                $remainingAttempts = $maxAttempts - $user->failed_login_attempts;
                $message = "Identifiants invalides. {$remainingAttempts} tentatives restantes.";
            }

            $user->save();
            SecurityLog::logLoginFailure($credentials['username']);

            throw ValidationException::withMessages([
                'username' => [$message],
            ]);
        }

        throw ValidationException::withMessages([
            'username' => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
