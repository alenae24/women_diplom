@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Редактирование записи #{{ $appointment->id }}</h2>
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">Назад к списку</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Клиент</label>
                    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">-- Выберите клиента --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('user_id', $appointment->user_id) == $client->id)>
                                {{ $client->name }} ({{ $client->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Мастер</label>
                    <select name="master_id" id="master_id" class="form-select @error('master_id') is-invalid @enderror" required>
                        <option value="">-- Выберите мастера --</option>
                        @foreach($masters as $master)
                            <option value="{{ $master->id }}" @selected(old('master_id', $appointment->master_id) == $master->id)>{{ $master->name }}</option>
                        @endforeach
                    </select>
                    @error('master_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Услуга</label>
                    <select name="service_id" id="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                        <option value="">-- Выберите услугу --</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" data-duration="{{ $service->duration }}" @selected(old('service_id', $appointment->service_id) == $service->id)>{{ $service->name }} ({{ $service->duration }} мин)</option>
                        @endforeach
                    </select>
                    @error('service_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Доступные даты</label>
                    <select name="date" id="date" class="form-select @error('date') is-invalid @enderror" required>
                        <option value="">-- Выберите мастера --</option>
                    </select>
                    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Свободное время</label>
                    <select name="time" id="time" class="form-select @error('time') is-invalid @enderror" required disabled>
                        <option value="">-- Сначала выберите дату --</option>
                    </select>
                    @error('time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Статус</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="pending" @selected(old('status', $appointment->status) == 'pending')>Ожидает</option>
                        <option value="confirmed" @selected(old('status', $appointment->status) == 'confirmed')>Подтверждена</option>
                        <option value="completed" @selected(old('status', $appointment->status) == 'completed')>Завершена</option>
                        <option value="cancelled" @selected(old('status', $appointment->status) == 'cancelled')>Отменена</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const masterSelect = document.getElementById('master_id');
    const serviceSelect = document.getElementById('service_id');
    const dateSelect = document.getElementById('date');
    const timeSelect = document.getElementById('time');

    // Сохраняем текущие значения для инициализации
    const currentDate = "{{ old('date', $appointment->start_time->format('Y-m-d')) }}";
    const currentTime = "{{ old('time', $appointment->start_time->format('H:i')) }}";

    function loadDates(masterId) {
        if (!masterId) {
            dateSelect.innerHTML = '<option value="">-- Выберите мастера --</option>';
            dateSelect.disabled = true;
            return;
        }
        fetch(`{{ route('admin.appointments.available-dates') }}?master_id=${masterId}`)
            .then(response => response.json())
            .then(dates => {
                dateSelect.innerHTML = '<option value="">-- Выберите дату --</option>';
                if (dates.length === 0) {
                    dateSelect.innerHTML += '<option disabled>Нет доступных дат</option>';
                } else {
                    dates.forEach(date => {
                        const option = document.createElement('option');
                        option.value = date.value;
                        option.textContent = date.label;
                        if (date.value === currentDate) option.selected = true;
                        dateSelect.appendChild(option);
                    });
                }
                dateSelect.disabled = false;
                // После загрузки дат, если есть текущая дата, загружаем время
                if (currentDate && masterSelect.value && serviceSelect.value) {
                    loadSlots();
                }
            })
            .catch(() => {
                dateSelect.innerHTML = '<option disabled>Ошибка загрузки</option>';
                dateSelect.disabled = false;
            });
    }

    function loadSlots() {
        const masterId = masterSelect.value;
        const serviceId = serviceSelect.value;
        const date = dateSelect.value;
        if (!masterId || !serviceId || !date) {
            timeSelect.innerHTML = '<option value="">-- Выберите все поля --</option>';
            timeSelect.disabled = true;
            return;
        }
        fetch(`{{ route('admin.appointments.free-slots') }}?master_id=${masterId}&service_id=${serviceId}&date=${date}`)
            .then(response => response.json())
            .then(slots => {
                timeSelect.innerHTML = '<option value="">-- Выберите время --</option>';
                if (slots.length === 0) {
                    timeSelect.innerHTML += '<option disabled>Нет свободных слотов</option>';
                } else {
                    slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot;
                        option.textContent = slot;
                        if (slot === currentTime) option.selected = true;
                        timeSelect.appendChild(option);
                    });
                }
                timeSelect.disabled = false;
            })
            .catch(() => {
                timeSelect.innerHTML = '<option disabled>Ошибка загрузки</option>';
                timeSelect.disabled = true;
            });
    }

    masterSelect.addEventListener('change', function() {
        loadDates(this.value);
        timeSelect.innerHTML = '<option value="">-- Сначала выберите дату --</option>';
        timeSelect.disabled = true;
    });

    dateSelect.addEventListener('change', function() {
        if (this.value) {
            loadSlots();
        } else {
            timeSelect.innerHTML = '<option value="">-- Выберите дату --</option>';
            timeSelect.disabled = true;
        }
    });

    serviceSelect.addEventListener('change', function() {
        if (masterSelect.value && dateSelect.value) {
            loadSlots();
        }
    });

    // Инициализация при загрузке страницы
    if (masterSelect.value) {
        loadDates(masterSelect.value);
    }
</script>
@endpush
@endsection