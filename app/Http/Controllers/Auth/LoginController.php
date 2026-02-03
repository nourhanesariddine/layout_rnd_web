<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\TokenRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    protected $userRepository;
    protected $tokenRepository;

    public function __construct(UserRepository $userRepository, TokenRepository $tokenRepository)
    {
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('search.index');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = $this->userRepository->getUserByEmail($request->email);

        if (!$user || md5($request->password) !== $user->password) {
            return redirect()->back()
                ->withErrors(['email' => 'Invalid credentials. Please check your email and password.'])
                ->withInput();
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        $accessToken = $this->tokenRepository->createAccessToken(
            $user->id,
            'App\\Models\\User',
            525600
        );

        $request->session()->put('access_token', $accessToken->token);

        return redirect()->route('search.index')
            ->with('success', 'Login successful! Welcome back.')
            ->with('access_token', $accessToken->token);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            \App\Models\AccessToken::where('tokenable_id', Auth::id())
                ->where('tokenable_type', 'App\\Models\\User')
                ->delete();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}
