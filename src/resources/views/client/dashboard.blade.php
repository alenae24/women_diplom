@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Карточка с информацией о клиенте -->
    <div class="card mb-4 shadow-sm" style="border-left: 4px solid #00677B;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title" style="color: #00677B;">{{ Auth::user()->name }}</h5>
                    <p class="card-text mb-1"><i class="bi bi-telephone-fill me-2" style="color: #00677B;"></i> {{ Auth::user()->phone ?? 'Не указан' }}</p>
                    <p class="card-text"><i class="bi bi-envelope-fill me-2" style="color: #00677B;"></i> {{ Auth::user()->email }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('client.profile.edit') }}" class="btn btn-outline-primary btn-sm">Редактировать профиль</a>
                </div>
            </div>
        </div>
    </div>

    <h2>Мои записи</h2>

    <h4>Предстоящие</h4>
    @if($upcoming->count())
        <div class="list-group mb-4">
            @foreach($upcoming as $app)
                <div class="list-group-item">
                    <strong>{{ $app->service->name }}</strong> — {{ $app->master->name }}<br>
                    Дата: {{ $app->start_time->format('d.m.Y H:i') }}<br>
                    Цена: {{ number_format($app->price, 0, ',', ' ') }} ₽<br>
                    <form action="{{ route('client.appointment.cancel', $app) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Отменить запись?')">Отменить</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <p>Нет предстоящих записей.</p>
    @endif

    <h4>Прошедшие</h4>
    @if($past->count())
        <div class="list-group">
            @foreach($past as $app)
                <div class="list-group-item">
                    {{ $app->service->name }} — {{ $app->master->name }}<br>
                    {{ $app->start_time->format('d.m.Y H:i') }}
                </div>
            @endforeach
        </div>
    @else
        <p>История пуста.</p>
    @endif
</div>
@endsection