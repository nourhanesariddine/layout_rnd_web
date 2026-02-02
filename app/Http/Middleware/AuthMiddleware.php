<?php

namespace App\Http\Middleware;

use App\Models\AccessToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated via session (primary check)
        if (Auth::check()) {
            return $next($request);
        }

        // Fallback: Check access token from session (optional for API/stateless)
        $accessToken = $request->session()->get('access_token');
        if ($accessToken) {
            $token = AccessToken::where('token', $accessToken)
                ->where('expires_at', '>', now())
                ->first();

            if ($token && $token->isValid()) {
                // Log the user in based on token
                $user = $token->tokenable;
                if ($user) {
                    Auth::login($user);
                    return $next($request);
                }
            }
        }

        // Not authenticated - redirect to login
        return redirect()->route('login')
            ->with('error', 'Please login to access this page.');
    }
}
