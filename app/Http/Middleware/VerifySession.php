<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifySession
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user) {
            $sessionIp = session('ip_address');
            $sessionAgent = session('user_agent');
            if (!$sessionIp || !$sessionAgent) {
                session(['ip_address' => $request->ip()]);
                session(['user_agent' => $request->header('User-Agent')]);
            } else {
                if ($sessionIp !== $request->ip() || $sessionAgent !== $request->header('User-Agent')) {
                    auth()->logout();
                    return redirect('/login')->withErrors(['session' => 'Session invalide.']);
                }
            }
        }
        return $next($request);
    }
}