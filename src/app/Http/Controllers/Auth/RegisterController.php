<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/email/verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u',],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'regex:/^\+7 \(\d{3}\) \d{3} - \d{2} - \d{2}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Введите имя.',
            'name.regex' => 'Имя может содержать только буквы, пробелы и дефисы.',
            'email.required' => 'Введите email.',
            'email.email' => 'Введите корректный адрес электронной почты.',
            'email.unique' => 'Пользователь с таким email уже зарегистрирован.',
            'phone.required' => 'Введите номер телефона.',
            'phone.regex' => 'Введите телефон в формате +7 (999) 999 - 99 - 99.',
            'password.required' => 'Введите пароль.',
            'password.min' => 'Пароль должен содержать не менее 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
    }
    
    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('login')
            ->with('status', 'Регистрация выполнена. На вашу почту отправлена ссылка для подтверждения email. Подтвердите email перед входом.');
    }
}
