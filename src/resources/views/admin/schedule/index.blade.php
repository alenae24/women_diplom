@extends('layouts.admin')

@section('content')
<style>
    .calendar-cell {
        height: 155px;
        vertical-align: top;
        padding: 8px;
        cursor: pointer;
        transition: 0.2s;
    }

    .calendar-cell:hover {
        background-color: #f8fbfc;
    }

    .calendar-other-month {
        background-color: #f1f3f5;
        color: #999;
    }

    .calendar-current-month {
        background-color: #ffffff;
    }

    .day-number {
        font-weight: 700;
        color: #00677B;
        margin-bottom: 6px;
    }

    .schedule-item {
        font-size: 12px;
        border-radius: 6px;
        padding: 6px 7px;
        margin-bottom: 5px;
        line-height: 1.25;
        text-align: left;
        border-left: 5px solid transparent;
        cursor: pointer;
        transition: 0.2s;
    }

    .schedule-item:hover {
        transform: translateX(2px);
        opacity: 0.9;
    }

    .schedule-off {
        background-color: #f8d7da !important;
        color: #721c24 !important;
    }

    .schedule-empty {
        font-size: 12px;
        color: #adb5bd;
    }

    .schedule-add-hint {
        font-size: 11px;
        color: #adb5bd;
        margin-top: 6px;
    }

    .master-filter-badge {
        cursor: pointer;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        opacity: 0.75;
        transition: 0.2s;
    }

    .master-filter-badge:hover,
    .master-filter-badge.active {
        opacity: 1;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 103, 123, 0.18);
    }

    .filter-empty {
        display: block;
        font-size: 12px;
        color: #adb5bd;
        margin-top: 4px;
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
            ['bg' => '#E6FCF5', 'border' => '#12B886', 'text' => '#087F5B'],
            ['bg' => '#F1F3F5', 'border' => '#495057', 'text' => '#343A40'],
        ];

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

        $currentMonth = \Carbon\Carbon::create($year, $month, 1);
        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-1">Календарь расписания мастеров</h2>
            <p class="text-muted mb-0">
                Общий просмотр и редактирование расписания всех мастеров на месяц
            </p>
        </div>

        <a href="{{ url('/admin') }}" class="btn btn-secondary">
            Назад
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <label for="masterSelect" class="form-label mb-0">Мастер:</label>

                    <select id="masterSelect" class="form-select" style="width: 260px;">
                        <option value="">Все мастера</option>

                        @foreach($masters as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.schedule.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}"
                       class="btn btn-outline-primary">
                        &laquo; {{ $monthNames[$prevMonth->month] }}
                    </a>

                    <h3 class="mb-0">
                        {{ $monthNames[$month] }} {{ $year }}
                    </h3>

                    <a href="{{ route('admin.schedule.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}"
                       class="btn btn-outline-primary">
                        {{ $monthNames[$nextMonth->month] }} &raquo;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        Нажмите на расписание мастера, чтобы изменить его. Нажмите на пустое место в дне, чтобы добавить расписание.
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <strong class="me-3">Фильтр по мастеру:</strong>

            <div class="d-flex flex-wrap gap-2 mt-2">
                <span class="badge master-filter-badge active"
                      data-master-filter=""
                      style="
                        background-color: #00677B;
                        color: #ffffff;
                        padding: 8px 10px;
                      ">
                    Все мастера
                </span>

                @foreach($masters as $item)
                    @php
                        $color = $masterColors[$item->id % count($masterColors)];
                    @endphp

                    <span class="badge master-filter-badge"
                          data-master-filter="{{ $item->id }}"
                          style="
                            background-color: {{ $color['bg'] }};
                            color: {{ $color['text'] }};
                            border-left: 5px solid {{ $color['border'] }};
                            padding: 8px 10px;
                          ">
                        {{ $item->name }}
                    </span>
                @endforeach
            </div>
        </div>
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
                            <td class="calendar-cell {{ $day['isCurrentMonth'] ? 'calendar-current-month' : 'calendar-other-month' }}"
                                data-date="{{ $day['date']->toDateString() }}"
                                data-current-month="{{ $day['isCurrentMonth'] ? 1 : 0 }}">
                                <div class="day-number">
                                    {{ $day['date']->day }}
                                </div>

                                @if($day['isCurrentMonth'])
                                    @php
                                        $daySchedules = $day['schedules']->sortBy(function ($schedule) {
                                            return sprintf(
                                                '%d_%s_%s',
                                                $schedule->is_day_off ? 1 : 0,
                                                $schedule->is_day_off ? '99:99' : $schedule->start_time,
                                                $schedule->master->name ?? ''
                                            );
                                        });
                                    @endphp

                                    @forelse($daySchedules as $schedule)
                                        @php
                                            $color = $masterColors[$schedule->master_id % count($masterColors)];
                                        @endphp

                                        <div class="schedule-item {{ $schedule->is_day_off ? 'schedule-off' : '' }}"
                                             data-date="{{ \Carbon\Carbon::parse($schedule->date)->toDateString() }}"
                                             data-master-id="{{ $schedule->master_id }}"
                                             data-is-day-off="{{ (int) $schedule->is_day_off }}"
                                             data-start-time="{{ !$schedule->is_day_off ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '' }}"
                                             data-end-time="{{ !$schedule->is_day_off ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '' }}"
                                             style="
                                                background-color: {{ $schedule->is_day_off ? '#f8d7da' : $color['bg'] }};
                                                border-left-color: {{ $color['border'] }};
                                                color: {{ $schedule->is_day_off ? '#721c24' : $color['text'] }};
                                             ">
                                            <strong>{{ $schedule->master->name ?? 'Мастер' }}</strong><br>

                                            @if($schedule->is_day_off)
                                                Выходной
                                            @else
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                                –
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            @endif
                                        </div>
                                    @empty
                                        <span class="schedule-empty">Нет расписания</span>
                                    @endforelse

                                    <div class="schedule-add-hint">
                                        + добавить
                                    </div>
                                @else
                                    <span class="schedule-empty">—</span>
                                @endif
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
        <form method="POST" id="scheduleForm" action="#">
            @csrf

            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="month" value="{{ $month }}">
            <input type="hidden" name="return_to" value="all">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalTitle">
                        Расписание на день
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="date" id="modalDate">

                    <div class="mb-3">
                        <label class="form-label">Мастер</label>
                        <select name="master_id_select" id="modalMasterId" class="form-select" required>
                            <option value="">-- Выберите мастера --</option>

                            @foreach($masters as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Дата</label>
                        <input type="text" id="modalDateView" class="form-control" disabled>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_day_off" value="1" class="form-check-input" id="isDayOff">
                        <label class="form-check-label" for="isDayOff">
                            Выходной
                        </label>
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
                window.location.href = `{{ route('admin.schedule.index', ['year' => $year, 'month' => $month]) }}`;
            }
        });
    }

    const scheduleModalElement = document.getElementById('scheduleModal');
    const scheduleModal = new bootstrap.Modal(scheduleModalElement);
    const scheduleForm = document.getElementById('scheduleForm');
    const modalTitle = document.getElementById('scheduleModalTitle');
    const modalDate = document.getElementById('modalDate');
    const modalDateView = document.getElementById('modalDateView');
    const modalMasterId = document.getElementById('modalMasterId');
    const isDayOff = document.getElementById('isDayOff');
    const startTime = document.getElementById('startTime');
    const endTime = document.getElementById('endTime');
    const timeFields = document.getElementById('timeFields');

    let activeMasterFilter = '';

    function formatDate(dateString) {
        const parts = dateString.split('-');
        return `${parts[2]}.${parts[1]}.${parts[0]}`;
    }

    function setFormAction(masterId) {
        scheduleForm.action = `/admin/schedule/${masterId}`;
    }

    function toggleTimeFields() {
        if (isDayOff.checked) {
            timeFields.style.display = 'none';
            startTime.value = '';
            endTime.value = '';
        } else {
            timeFields.style.display = 'block';

            if (!startTime.value) {
                startTime.value = '11:00';
            }

            if (!endTime.value) {
                endTime.value = '19:00';
            }
        }
    }

    function applyMasterFilter(selectedMasterId) {
        activeMasterFilter = selectedMasterId || '';

        document.querySelectorAll('.filter-empty').forEach(item => {
            item.remove();
        });

        document.querySelectorAll('.calendar-cell[data-current-month="1"]').forEach(cell => {
            const originalEmpty = cell.querySelector('.schedule-empty');
            const scheduleItems = cell.querySelectorAll('.schedule-item');

            let visibleCount = 0;

            scheduleItems.forEach(item => {
                const itemMasterId = item.dataset.masterId;

                if (!activeMasterFilter || itemMasterId === activeMasterFilter) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (originalEmpty) {
                originalEmpty.style.display = activeMasterFilter ? 'none' : '';
            }

            if (activeMasterFilter && visibleCount === 0) {
                const emptyText = document.createElement('span');
                emptyText.classList.add('filter-empty');
                emptyText.textContent = 'Нет расписания у мастера';

                const addHint = cell.querySelector('.schedule-add-hint');

                if (addHint) {
                    cell.insertBefore(emptyText, addHint);
                } else {
                    cell.appendChild(emptyText);
                }
            }
        });
    }

    document.querySelectorAll('.master-filter-badge').forEach(badge => {
        badge.addEventListener('click', function () {
            const selectedMasterId = this.dataset.masterFilter || '';

            document.querySelectorAll('.master-filter-badge').forEach(item => {
                item.classList.remove('active');
            });

            this.classList.add('active');

            applyMasterFilter(selectedMasterId);
        });
    });

    document.querySelectorAll('.schedule-item').forEach(item => {
        item.addEventListener('click', function (event) {
            event.stopPropagation();

            const date = this.dataset.date;
            const masterId = this.dataset.masterId;
            const dayOff = this.dataset.isDayOff === '1';

            modalTitle.textContent = 'Редактировать расписание';
            modalDate.value = date;
            modalDateView.value = formatDate(date);
            modalMasterId.value = masterId;
            isDayOff.checked = dayOff;

            if (dayOff) {
                startTime.value = '';
                endTime.value = '';
            } else {
                startTime.value = this.dataset.startTime || '11:00';
                endTime.value = this.dataset.endTime || '19:00';
            }

            setFormAction(masterId);
            toggleTimeFields();
            scheduleModal.show();
        });
    });

    document.querySelectorAll('.calendar-cell').forEach(cell => {
        cell.addEventListener('click', function () {
            if (this.dataset.currentMonth !== '1') {
                return;
            }

            const date = this.dataset.date;

            modalTitle.textContent = 'Добавить расписание';
            modalDate.value = date;
            modalDateView.value = formatDate(date);
            modalMasterId.value = activeMasterFilter || '';
            isDayOff.checked = false;
            startTime.value = '11:00';
            endTime.value = '19:00';

            if (activeMasterFilter) {
                setFormAction(activeMasterFilter);
            } else {
                scheduleForm.action = '#';
            }

            toggleTimeFields();
            scheduleModal.show();
        });
    });

    modalMasterId.addEventListener('change', function () {
        if (this.value) {
            setFormAction(this.value);
        } else {
            scheduleForm.action = '#';
        }
    });

    isDayOff.addEventListener('change', toggleTimeFields);

    scheduleForm.addEventListener('submit', function (event) {
        const masterId = modalMasterId.value;

        if (!masterId) {
            event.preventDefault();
            alert('Выберите мастера.');
            return;
        }

        setFormAction(masterId);
    });
</script>
@endpush
@endsection