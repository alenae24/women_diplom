@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center">{{ __('Вход в систему') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Пароль') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">{{ __('Запомнить меня') }}</label>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('Войти') }}</button>
                            @if (Route::has('password.request'))
                                <a class="btn btn-link text-center" href="{{ route('password.request') }}">{{ __('Забыли пароль?') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    {{ __('Нет аккаунта?') }} <a href="{{ route('register') }}">{{ __('Зарегистрироваться') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection