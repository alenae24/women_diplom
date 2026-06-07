@extends('layouts.admin')

@section('content')
<style>
    .calendar-cell {
        cursor: pointer;
        height: 95px;
        vertical-align: middle;
        transition: 0.2s;
    }

    .calendar-cell:hover {
        opacity: 0.85;
    }

    .calendar-work-day {
        background-color: #d4edda !important;
        color: #155724 !important;
    }

    .calendar-day-off {
        background-color: #f8d7da !important;
        color: #721c24 !important;
    }

    .calendar-not-set {
        background-color: #f8f9fa !important;
        color: #6c757d !important;
    }

    .calendar-other-month {
        background-color: #dee2e6 !important;
        color: #6c757d !important;
    }

    .day-number {
        font-weight: bold;
        font-size: 16px;
    }

    .day-status {
        font-size: 0.8rem;
        margin-top: 4px;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-2">Календарь расписания</h2>

            <div class="d-flex align-items-center gap-2">
                <label for="masterSelect" class="form-label mb-0">Мастер:</label>

                <select id="masterSelect" class="form-select" style="width: 260px;">
                    <option value="">Все мастера</option>

                    @foreach($masters as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $master->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <a href="{{ route('admin.schedule.index', ['year' => $year, 'month' => $month]) }}" class="btn btn-secondary">
            Назад к общему календарю
        </a>
    </div>

    @php
        $monthNames = [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь',
        ];

        $prevDate = \Carbon\Carbon::create($year, $month, 1)->subMonth();
        $nextDate = \Carbon\Carbon::create($year, $month, 1)->addMonth();
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.schedule.calendar', [
            'master' => $master->id,
            'year' => $prevDate->year,
            'month' => $prevDate->month
        ]) }}" class="btn btn-outline-primary">
            &laquo; {{ $monthNames[$prevDate->month] }}
        </a>

        <h3>{{ $monthNames[$month] }} {{ $year }}</h3>

        <a href="{{ route('admin.schedule.calendar', [
            'master' => $master->id,
            'year' => $nextDate->year,
            'month' => $nextDate->month
        ]) }}" class="btn btn-outline-primary">
            {{ $monthNames[$nextDate->month] }} &raquo;
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>Пн</th>
                    <th>Вт</th>
                    <th>Ср</th>
                    <th>Чт</th>
                    <th>Пт</th>
                    <th>Сб</th>
                    <th>Вс</th>
                </tr>
            </thead>

            <tbody>
                @foreach($calendar as $week)
                    <tr>
                        @foreach($week as $day)
                            @php
                                $cellClass = 'calendar-cell';
                                $content = '';

                                if (!$day['isCurrentMonth']) {
                                    $cellClass .= ' calendar-other-month';
                                    $content = '—';
                                } elseif ($day['schedule']) {
                                    if ($day['schedule']->is_day_off) {
                                        $cellClass .= ' calendar-day-off';
                                        $content = 'Выходной';
                                    } else {
                                        $cellClass .= ' calendar-work-day';
                                        $content = \Carbon\Carbon::parse($day['schedule']->start_time)->format('H:i') .
                                            ' – ' .
                                            \Carbon\Carbon::parse($day['schedule']->end_time)->format('H:i');
                                    }
                                } else {
                                    $cellClass .= ' calendar-not-set';
                                    $content = 'Не задано';
                                }
                            @endphp

                            <td class="align-middle {{ $cellClass }}"
                                data-date="{{ $day['date']->toDateString() }}"
                                data-schedule-id="{{ $day['schedule']->id ?? '' }}"
                                data-is-day-off="{{ $day['schedule'] ? (int) $day['schedule']->is_day_off : 0 }}"
                                data-start-time="{{ $day['schedule'] && !$day['schedule']->is_day_off ? \Carbon\Carbon::parse($day['schedule']->start_time)->format('H:i') : '' }}"
                                data-end-time="{{ $day['schedule'] && !$day['schedule']->is_day_off ? \Carbon\Carbon::parse($day['schedule']->end_time)->format('H:i') : '' }}">

                                <div class="day-number">{{ $day['date']->day }}</div>
                                <div class="day-status">{{ $content }}</div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="scheduleForm" action="{{ route('admin.schedule.store', $master->id) }}">
            @csrf

            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="month" value="{{ $month }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Расписание на день</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="date" id="modalDate">

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_day_off" value="1" class="form-check-input" id="isDayOff">
                        <label class="form-check-label" for="isDayOff">Выходной</label>
                    </div>

                    <div id="timeFields">
                        <div class="mb-3">
                            <label class="form-label">Начало работы</label>
                            <input type="time" name="start_time" id="startTime" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Конец работы</label>
                            <input type="time" name="end_time" id="endTime" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Отмена
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Сохранить
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const masterSelect = document.getElementById('masterSelect');

    if (masterSelect) {
        masterSelect.addEventListener('change', function () {
            const masterId = this.value;

            if (masterId) {
                window.location.href = `/admin/schedule/${masterId}?year={{ $year }}&month={{ $month }}`;
            } else {
                window.location.href = `/admin/schedule?year={{ $year }}&month={{ $month }}`;
            }
        });
    }

    document.querySelectorAll('td[data-date]').forEach(cell => {
        cell.addEventListener('click', (e) => {
            if (e.target.tagName === 'A') return;

            const date = cell.dataset.date;
            const hasSchedule = cell.dataset.scheduleId !== '';
            const isDayOff = cell.dataset.isDayOff == '1' || cell.dataset.isDayOff === 'true';
            const startTime = cell.dataset.startTime;
            const endTime = cell.dataset.endTime;

            document.getElementById('modalDate').value = date;
            document.getElementById('isDayOff').checked = isDayOff;

            if (isDayOff) {
                document.getElementById('startTime').value = '';
                document.getElementById('endTime').value = '';
            } else {
                document.getElementById('startTime').value = hasSchedule && startTime ? startTime : '11:00';
                document.getElementById('endTime').value = hasSchedule && endTime ? endTime : '19:00';
            }

            const timeFields = document.getElementById('timeFields');
            timeFields.style.display = isDayOff ? 'none' : 'block';

            const modal = new bootstrap.Modal(document.getElementById('scheduleModal'));
            modal.show();
        });
    });

    document.getElementById('isDayOff').addEventListener('change', function(e) {
        const timeFields = document.getElementById('timeFields');

        if (e.target.checked) {
            timeFields.style.display = 'none';
            document.getElementById('startTime').value = '';
            document.getElementById('endTime').value = '';
        } else {
            timeFields.style.display = 'block';

            if (!document.getElementById('startTime').value) {
                document.getElementById('startTime').value = '11:00';
            }

            if (!document.getElementById('endTime').value) {
                document.getElementById('endTime').value = '19:00';
            }
        }
    });
</script>
@endpush
@endsection