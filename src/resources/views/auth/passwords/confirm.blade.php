@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center">{{ __('Подтверждение пароля') }}</div>
                <div class="card-body">
                    <p class="mb-3">{{ __('Пожалуйста, подтвердите пароль перед продолжением.') }}</p>
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Пароль') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autofocus>
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">{{ __('Подтвердить') }}</button>
                            @if (Route::has('password.request'))
                                <a class="btn btn-link mt-2" href="{{ route('password.request') }}">{{ __('Забыли пароль?') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection