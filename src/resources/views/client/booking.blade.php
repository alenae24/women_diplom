@extends('layouts.app')

@section('content')
<style>
    .booking-card {
        border-radius: 18px;
        overflow: hidden;
    }

    .booking-header {
        background-color: #ECF9FC;
        color: #00677B;
        border-bottom: 1px solid #d7eef4;
    }

    .choice-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .choice-btn {
        border: 1px solid #d7e3e6;
        background-color: #ffffff;
        color: #333;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 14px;
        transition: 0.2s;
    }

    .choice-btn:hover {
        border-color: #00677B;
        color: #00677B;
    }

    .choice-btn.active {
        background-color: #00677B;
        border-color: #00677B;
        color: #ffffff;
    }

    .choice-btn:disabled {
        background-color: #f3f3f3;
        color: #999;
        border-color: #e0e0e0;
        cursor: not-allowed;
    }

    .booking-step-title {
        color: #00677B;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .booking-empty {
        color: #777;
        font-size: 14px;
        margin-bottom: 0;
    }
</style>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card booking-card border-0 shadow-sm">
                <div class="card-header booking-header text-center py-3">
                    <h2 class="mb-0">Запись на услугу</h2>
                </div>

                <div class="card-body p-4">
                    <form id="bookingForm" action="{{ route('client.booking.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="date" id="selected_date">
                        <input type="hidden" name="time" id="selected_time">

                        <div class="mb-4">
                            <label for="service_id" class="form-label booking-step-title">Услуга</label>
                            <select name="service_id" id="service_id" class="form-select" required>
                                <option value="">-- Выберите услугу --</option>

                                @foreach($services as $service)
                                    <option value="{{ $service->id }}"
                                        {{ old('service_id', $selectedServiceId ?? '') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} — от {{ number_format($service->base_price, 0, ',', ' ') }} ₽
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="master_id" class="form-label booking-step-title">Мастер</label>
                            <select name="master_id" id="master_id" class="form-select" required>
                                <option value="">-- Выберите мастера --</option>

                                @foreach($masters as $master)
                                    <option value="{{ $master->id }}">
                                        {{ $master->name }} ({{ $master->level_name ?? $master->level }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <div class="booking-step-title">Доступные даты</div>
                            <div id="dateButtons" class="choice-grid">
                                <p class="booking-empty">Сначала выберите мастера</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="booking-step-title">Свободное время</div>
                            <div id="timeButtons" class="choice-grid">
                                <p class="booking-empty">Сначала выберите дату</p>
                            </div>
                        </div>

                        <button type="submit" id="submitBooking" class="btn btn-primary w-100" disabled>
                            Записаться
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    const $serviceSelect = $('#service_id');
    const $masterSelect = $('#master_id');
    const $dateButtons = $('#dateButtons');
    const $timeButtons = $('#timeButtons');
    const $selectedDate = $('#selected_date');
    const $selectedTime = $('#selected_time');
    const $submitButton = $('#submitBooking');

    function resetDates(message = 'Сначала выберите мастера') {
        $selectedDate.val('');
        $dateButtons.html('<p class="booking-empty">' + message + '</p>');
        resetTimes();
        checkSubmit();
    }

    function resetTimes(message = 'Сначала выберите дату') {
        $selectedTime.val('');
        $timeButtons.html('<p class="booking-empty">' + message + '</p>');
        checkSubmit();
    }

    function checkSubmit() {
        const hasService = $serviceSelect.val();
        const hasMaster = $masterSelect.val();
        const hasDate = $selectedDate.val();
        const hasTime = $selectedTime.val();

        $submitButton.prop('disabled', !(hasService && hasMaster && hasDate && hasTime));
    }

    function loadDates(masterId) {
        resetDates('Загрузка дат...');

        $.ajax({
            url: '{{ route("client.available-dates") }}',
            type: 'GET',
            data: { master_id: masterId },
            success: function(dates) {
                $dateButtons.empty();

                if (!dates.length) {
                    $dateButtons.html('<p class="booking-empty">Нет доступных дат</p>');
                    return;
                }

                $.each(dates, function(i, d) {
                    const button = $('<button>', {
                        type: 'button',
                        class: 'choice-btn date-btn',
                        text: d.label,
                        'data-date': d.value
                    });

                    $dateButtons.append(button);
                });
            },
            error: function() {
                $dateButtons.html('<p class="booking-empty">Ошибка загрузки дат</p>');
            }
        });
    }

    function loadTimes(serviceId, masterId, date) {
        resetTimes('Загрузка времени...');

        $.ajax({
            url: '{{ route("client.free-time") }}',
            type: 'GET',
            data: {
                service_id: serviceId,
                master_id: masterId,
                date: date
            },
            success: function(slots) {
                $timeButtons.empty();

                if (!slots.length) {
                    $timeButtons.html('<p class="booking-empty">Нет свободного времени</p>');
                    return;
                }

                $.each(slots, function(i, slot) {
                    const button = $('<button>', {
                        type: 'button',
                        class: 'choice-btn time-btn',
                        text: slot,
                        'data-time': slot
                    });

                    $timeButtons.append(button);
                });
            },
            error: function() {
                $timeButtons.html('<p class="booking-empty">Ошибка загрузки времени</p>');
            }
        });
    }

    $serviceSelect.on('change', function() {
        resetTimes();

        if ($masterSelect.val()) {
            loadDates($masterSelect.val());
        }

        checkSubmit();
    });

    $masterSelect.on('change', function() {
        const masterId = $(this).val();

        if (!masterId) {
            resetDates();
            return;
        }

        loadDates(masterId);
        checkSubmit();
    });

    $(document).on('click', '.date-btn', function() {
        $('.date-btn').removeClass('active');
        $(this).addClass('active');

        const date = $(this).data('date');
        const serviceId = $serviceSelect.val();
        const masterId = $masterSelect.val();

        $selectedDate.val(date);
        $selectedTime.val('');
        checkSubmit();

        if (!serviceId) {
            resetTimes('Сначала выберите услугу');
            return;
        }

        loadTimes(serviceId, masterId, date);
    });

    $(document).on('click', '.time-btn', function() {
        $('.time-btn').removeClass('active');
        $(this).addClass('active');

        $selectedTime.val($(this).data('time'));
        checkSubmit();
    });

    $('#bookingForm').on('submit', function(e) {
        if (!$serviceSelect.val() || !$masterSelect.val() || !$selectedDate.val() || !$selectedTime.val()) {
            e.preventDefault();
            alert('Выберите услугу, мастера, дату и время записи.');
        }
    });

    checkSubmit();
});
</script>
@endpush