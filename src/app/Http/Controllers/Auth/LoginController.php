<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function redirectTo()
    {
        if (auth()->check() && !auth()->user()->hasVerifiedEmail()) {
            return '/email/verify';
        }

        if (auth()->check() && auth()->user()->role === 'admin') {
            return '/admin';
        }

        return '/dashboard';
    }

    protected function authenticated(Request $request, $user)
    {
        if (! $user->hasVerifiedEmail()) {
            $this->guard()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Подтвердите email перед входом в аккаунт.',
                ]);
        }

        if ($user->role === 'admin') {
            return redirect('/admin');
        }

        return redirect('/dashboard');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => ['Неверный email или пароль.'],
        ]);
    }
}