@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center">{{ __('Регистрация') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Имя') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            @error('email')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">{{ __('Телефон') }}</label>
                            <input id="phone"
                                    type="text"
                                    class="form-control phone-mask @error('phone') is-invalid @enderror"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="+7 (999) 999 - 99 - 99"
                                    required>
                            @error('phone')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Пароль') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">{{ __('Подтверждение пароля') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">{{ __('Зарегистрироваться') }}</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    {{ __('Уже есть аккаунт?') }} <a href="{{ route('login') }}">{{ __('Войти') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection