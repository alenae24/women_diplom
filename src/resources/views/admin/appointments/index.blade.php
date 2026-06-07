@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Записи клиентов</h2>
        <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">+ Создать запись</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Клиент</th>
                    <th>Мастер</th>
                    <th>Услуга</th>
                    <th>Дата и время</th>
                    <th>Цена</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $app)
                <tr>
                    <td>{{ $app->id }}</td>
                    <td>{{ $app->user->name ?? '—' }}</td>
                    <td>{{ $app->master->name }}</td>
                    <td>{{ $app->service->name }}</td>
                    <td>{{ $app->start_time->format('d.m.Y H:i') }}</td>
                    <td>{{ number_format($app->price, 0, ',', ' ') }} ₽</td>
                    <td>
                        @php
                            $statusLabels = [
                                'pending' => 'Ожидает',
                                'confirmed' => 'Подтверждена',
                                'completed' => 'Завершена',
                                'cancelled' => 'Отменена',
                            ];
                            $statusClass = match($app->status) {
                                'pending' => 'bg-warning text-dark',
                                'confirmed' => 'bg-primary text-white',
                                'completed' => 'bg-success text-white',
                                'cancelled' => 'bg-danger text-white',
                                default => 'bg-secondary text-white',
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusLabels[$app->status] ?? $app->status }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.appointments.edit', $app) }}" class="btn btn-sm btn-info">Редактировать</a>
                        <form action="{{ route('admin.appointments.destroy', $app) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить запись?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center">Нет записей</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $appointments->links() }}
</div>
@endsection