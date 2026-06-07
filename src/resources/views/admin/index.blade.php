@extends('layouts.admin')

@section('content')
<style>
    .dashboard-schedule-item {
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 8px;
        border-left: 5px solid transparent;
        font-size: 14px;
    }

    .dashboard-schedule-work {
        background-color: #d4edda;
        border-left-color: #198754;
        color: #155724;
    }

    .dashboard-schedule-off {
        background-color: #f8d7da;
        border-left-color: #dc3545;
        color: #721c24;
    }

    .dashboard-stat-card {
        background-color: #ECF9FC;
        border-radius: 10px;
        padding: 16px;
        height: 100%;
        border-left: 5px solid #00677B;
    }
    
    .dashboard-stat-card .stat-label {
        font-size: 13px;
        color: #6c757d;
        margin-bottom: 6px;
    }
    
    .dashboard-stat-card .stat-value {
        font-size: 22px;
        font-weight: 700;
        color: #00677B;
        margin-bottom: 0;
    }
    
    .dashboard-appointment-item {
        border-radius: 8px;
        padding: 12px 14px;
        margin-bottom: 8px;
        border-left: 5px solid transparent;
        font-size: 14px;
    }
    
    .dashboard-appointment-actions {
        display: flex;
        gap: 6px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .dashboard-status-badge {
        font-size: 11px;
        padding: 5px 8px;
    }
</style>

<div class="container-fluid">
    @php
        $masterColors = [
            ['bg' => '#D8F3DC', 'border' => '#2D6A4F', 'text' => '#1B4332'],
            ['bg' => '#DDEBFF', 'border' => '#2F80ED', 'text' => '#1D4E89'],
            ['bg' => '#FFF3CD', 'border' => '#F0AD4E', 'text' => '#7A4F01'],
            ['bg' => '#F3D9FA', 'border' => '#9C36B5', 'text' => '#5F0F6B'],
            ['bg' => '#FFE3E3', 'border' => '#E03131', 'text' => '#7D1A1A'],
            ['bg' => '#E0FBFC', 'border' => '#008C99', 'text' => '#005F66'],
        ];
        
        $currentDate = \Carbon\Carbon::parse($selectedDate);
        $prevDate = $currentDate->copy()->subDay();
        $nextDate = $currentDate->copy()->addDay();

        $monthNames = [
            1 => 'января',
            2 => 'февраля',
            3 => 'марта',
            4 => 'апреля',
            5 => 'мая',
            6 => 'июня',
            7 => 'июля',
            8 => 'августа',
            9 => 'сентября',
            10 => 'октября',
            11 => 'ноября',
            12 => 'декабря',
        ];

        $dateTitle = $currentDate->format('d') . ' ' . $monthNames[(int) $currentDate->format('m')] . ' ' . $currentDate->format('Y');
    @endphp

    <h2 class="mb-4">Панель управления</h2>

    <div class="row g-4">
        <!-- Левая колонка: записи на выбранную дату -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-calendar-day"></i>
                        Записи на {{ $dateTitle }}
                    </div>

                    <div>
                        <a href="{{ route('admin.dashboard', ['date' => $prevDate->toDateString()]) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-chevron-left"></i>
                        </a>

                        <a href="{{ route('admin.dashboard', ['date' => $nextDate->toDateString()]) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($todayAppointments->count())
                        <div class="p-3">
                            @foreach($todayAppointments as $app)
                                @php
                                    $color = $masterColors[$app->master_id % count($masterColors)];
                    
                                    $statusText = [
                                        'confirmed' => 'Подтверждена',
                                        'cancelled' => 'Отменена',
                                    ][$app->status] ?? $app->status;
                                @endphp
                    
                                <div class="dashboard-appointment-item"
                                     style="
                                        background-color: {{ $color['bg'] }};
                                        border-left-color: {{ $color['border'] }};
                                        color: {{ $color['text'] }};
                                     ">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <div>
                                            <div class="fw-bold mb-1">
                                                {{ $app->start_time->format('H:i') }}
                                                —
                                                {{ $app->master->name ?? 'Мастер' }}
                                            </div>
                    
                                            <div>
                                                {{ $app->service->name ?? 'Услуга не указана' }}
                                            </div>
                    
                                            @if($app->user)
                                                <div class="small mt-1">
                                                    Клиент: {{ $app->user->name }}
                                                </div>
                                            @endif
                    
                                            <div class="mt-2">
                                                <span class="badge bg-primary dashboard-status-badge">
                                                    {{ number_format($app->price, 0, ',', ' ') }} ₽
                                                </span>
                    
                                                <span class="badge bg-success dashboard-status-badge">
                                                    {{ $statusText }}
                                                </span>
                                            </div>
                                        </div>
                    
                                        <div class="dashboard-appointment-actions">
                                            @if($app->status !== 'confirmed')
                                                <form method="POST" action="{{ route('admin.appointments.status', $app) }}">
                                                    @csrf
                                                    @method('PATCH')
                    
                                                    <input type="hidden" name="status" value="confirmed">
                    
                                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                                        Подтвердить
                                                    </button>
                                                </form>
                                            @endif
                    
                                            <form method="POST"
                                                  action="{{ route('admin.appointments.status', $app) }}"
                                                  onsubmit="return confirm('Отменить запись?')">
                                                @csrf
                                                @method('PATCH')
                    
                                                <input type="hidden" name="status" value="cancelled">
                    
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Отменить
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-calendar-x fs-1"></i>
                            <p class="mt-2">На эту дату записей нет</p>
                        </div>
                    @endif
                </div>

                <div class="card-footer text-end d-flex justify-content-between">
                    <a href="{{ route('admin.appointments.create') }}" class="btn btn-sm btn-primary">
                        + Добавить запись
                    </a>

                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-outline-primary">
                        Все записи
                    </a>
                </div>
            </div>
        </div>

        <!-- Правая колонка: расписание мастеров на выбранный день -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-calendar-week"></i>
                        Расписание мастеров на {{ $dateTitle }}
                    </div>

                    <div>
                        <a href="{{ route('admin.dashboard', ['date' => $prevDate->toDateString()]) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-chevron-left"></i>
                        </a>

                        <a href="{{ route('admin.dashboard', ['date' => $nextDate->toDateString()]) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($daySchedules->count())
                        @foreach($daySchedules as $schedule)
                            <div class="dashboard-schedule-item {{ $schedule->is_day_off ? 'dashboard-schedule-off' : 'dashboard-schedule-work' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $schedule->master->name ?? 'Мастер' }}</strong>
                                        <br>

                                        @if($schedule->is_day_off)
                                            <span>Выходной</span>
                                        @else
                                            <span>
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                                —
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </span>
                                        @endif
                                    </div>

                                    <a href="{{ route('admin.schedule.calendar', [
                                        'master' => $schedule->master_id,
                                        'year' => $currentDate->year,
                                        'month' => $currentDate->month,
                                    ]) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Изменить
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-clock-history fs-1"></i>
                            <p class="mt-2">На эту дату расписание не задано</p>
                        </div>
                    @endif
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('admin.schedule.index', [
                        'year' => $currentDate->year,
                        'month' => $currentDate->month,
                    ]) }}"
                       class="btn btn-sm btn-primary">
                        Открыть общий календарь
                    </a>
                </div>
            </div>

            <!-- Сводка дня -->
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-graph-up"></i>
                    Сводка дня
                </div>
            
                <div class="card-body">
                    @php
                        $appointmentsCount = $todayAppointments->count();
                        $dayRevenue = $todayAppointments->sum('price');
                        $workingMastersCount = $daySchedules->where('is_day_off', false)->count();
                        $dayOffMastersCount = $daySchedules->where('is_day_off', true)->count();
                    @endphp
            
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="dashboard-stat-card">
                                <div class="stat-label">
                                    Записей на день
                                </div>
                                <p class="stat-value">
                                    {{ $appointmentsCount }}
                                </p>
                            </div>
                        </div>
            
                        <div class="col-md-6">
                            <div class="dashboard-stat-card">
                                <div class="stat-label">
                                    Выручка по записям
                                </div>
                                <p class="stat-value">
                                    {{ number_format($dayRevenue, 0, ',', ' ') }} ₽
                                </p>
                            </div>
                        </div>
            
                        <div class="col-md-6">
                            <div class="dashboard-stat-card">
                                <div class="stat-label">
                                    Работают мастера
                                </div>
                                <p class="stat-value">
                                    {{ $workingMastersCount }}
                                </p>
                            </div>
                        </div>
            
                        <div class="col-md-6">
                            <div class="dashboard-stat-card">
                                <div class="stat-label">
                                    Выходных у мастеров
                                </div>
                                <p class="stat-value">
                                    {{ $dayOffMastersCount }}
                                </p>
                            </div>
                        </div>
                    </div>
            
                    <div class="mt-3 text-muted small">
                        Сводка считается по выбранной дате: {{ $dateTitle }}.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection