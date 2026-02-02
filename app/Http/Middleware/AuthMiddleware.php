<?php

namespace App\Http\Middleware;

use App\Models\AccessToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
   
    public function handle(Request $request, Closure $next): Response
    {
       
        if (Auth::check()) {
            return $next($request);
        }

      
        $accessToken = $request->session()->get('access_token');
        if ($accessToken) {
            $token = AccessToken::where('token', $accessToken)
                ->where('expires_at', '>', now())
                ->first();

            if ($token && $token->isValid()) {
             
                $user = $token->tokenable;
                if ($user) {
                    Auth::login($user);
                    return $next($request);
                }
            }
        }

     
        return redirect()->route('login')
            ->with('error', 'Please login to access this page.');
    }
}
