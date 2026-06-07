@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">{{ __('Подтверждение email') }}</div>
                <div class="card-body text-center">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">{{ __('Ссылка для подтверждения отправлена на ваш email.') }}</div>
                    @endif
                    <p>{{ __('Прежде чем продолжить, проверьте почту для получения ссылки подтверждения.') }}</p>
                    <p>{{ __('Если вы не получили письмо') }},</p>
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link">{{ __('нажмите здесь, чтобы запросить повторно') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection