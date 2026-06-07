@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Расписание мастера: {{ $master->name }}</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-secondary mb-3">← Назад к мастерам</a>

    <div class="card mb-4">
        <div class="card-header">Добавить рабочий день или выходной</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.schedule.store', $master->id) }}">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="date" class="form-label">Дата</label>
                        <input type="date" name="date" id="date" class="form-control" required value="{{ old('date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="start_time" class="form-label">Начало (чч:мм)</label>
                        <input type="time" name="start_time" id="start_time" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="end_time" class="form-label">Конец (чч:мм)</label>
                        <input type="time" name="end_time" id="end_time" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" name="is_day_off" id="is_day_off" class="form-check-input" value="1">
                            <label class="form-check-label" for="is_day_off">Выходной</label>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <h4>Существующее расписание</h4>
    @if($schedules->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Начало</th>
                    <th>Конец</th>
                    <th>Тип</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $sched)
                    <tr>
                        <td>{{ $sched->date }}</td>
                        <td>{{ $sched->is_day_off ? '—' : $sched->start_time }}</td>
                        <td>{{ $sched->is_day_off ? '—' : $sched->end_time }}</td>
                        <td>{{ $sched->is_day_off ? 'Выходной' : 'Рабочий' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.schedule.destroy', $sched->id) }}" onsubmit="return confirm('Удалить?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Расписание не задано.</p>
    @endif
</div>
@endsection