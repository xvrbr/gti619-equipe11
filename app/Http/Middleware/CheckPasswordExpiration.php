<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Carbon\Carbon;

class CheckPasswordExpiration
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $expirationDays = (int)SystemSetting::getValue(SystemSetting::PASSWORD_EXPIRATION_DAYS, 0);

            // Si l'expiration est activée (> 0 jours)
            if ($expirationDays > 0 && $user->password_changed_at) {
                $expirationDate = $user->password_changed_at->addDays($expirationDays);

                // Si le mot de passe est expiré ou a été réinitialisé par l'admin
                if (Carbon::now() > $expirationDate || $user->force_password_change) {
                    // Autoriser l'accès à la page de changement de mot de passe et à la déconnexion
                    $allowedRoutes = ['password.change', 'password.change.submit', 'logout'];

                    if (!in_array($request->route()->getName(), $allowedRoutes)) {
                        return redirect()->route('password.change')
                            ->with('warning', 'Vous devez changer votre mot de passe pour continuer. Vous pouvez aussi vous déconnecter et revenir plus tard.');
                    }
                }
            }
        }

        return $next($request);
    }
}
