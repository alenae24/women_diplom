@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Цены мастеров на услуги</h2>
    </div>

    <!-- Фильтр по мастеру -->
    <div class="card mb-3">
        <div class="card-header">Фильтр</div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.prices.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Мастер</label>
                        <select name="master_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Все мастера</option>

                            @foreach($masters as $master)
                                <option value="{{ $master->id }}" {{ request('master_id') == $master->id ? 'selected' : '' }}>
                                    {{ $master->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <a href="{{ route('admin.prices.index') }}" class="btn btn-secondary w-100">
                            Сбросить
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Форма добавления -->
    <div class="card mb-3">
        <div class="card-header">Назначить цену</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.prices.store') }}">
                @csrf

                <div class="row g-3 align-items-start">
                    <div class="col-md-4">
                        <label class="form-label">Мастер</label>
                        <select name="master_id" class="form-select" required>
                            <option value="">-- Мастер --</option>

                            @foreach($masters as $master)
                                <option value="{{ $master->id }}">
                                    {{ $master->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Услуга</label>
                        <select name="service_id" class="form-select" required>
                            <option value="">-- Услуга --</option>

                            @foreach($services as $service)
                                <option value="{{ $service->id }}">
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Цена</label>
                        <input type="number"
                               step="0.01"
                               name="price"
                               placeholder="Цена, ₽"
                               class="form-control">

                        <small class="text-muted">
                            Необязательно. Если не указано, используется базовая цена услуги.
                        </small>
                    </div>

                    <div class="col-md-1">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            Сохранить
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Таблица цен -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Мастер</th>
                    <th>Услуга</th>
                    <th>Цена</th>
                    <th>Действия</th>
                </tr>
            </thead>

            <tbody>
                @forelse($prices as $price)
                    <tr>
                        <td>{{ $price->master->name ?? '—' }}</td>
                        <td>{{ $price->service->name ?? '—' }}</td>

                        <td>
                            @if($price->price)
                                {{ number_format($price->price, 0, ',', ' ') }} ₽
                            @else
                                {{ number_format($price->service->base_price, 0, ',', ' ') }} ₽
                                <span class="text-muted">(базовая)</span>
                            @endif
                        </td>

                        <td style="white-space: nowrap;">
                            <button type="button"
                                    class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editPriceModal"
                                    data-id="{{ $price->id }}"
                                    data-master="{{ $price->master_id }}"
                                    data-service="{{ $price->service_id }}"
                                    data-price="{{ $price->price }}">
                                <i class="bi bi-pencil"></i>
                                Редактировать
                            </button>

                            <form action="{{ route('admin.prices.destroy', $price->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Удалить цену?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            @if(request('master_id'))
                                Для выбранного мастера цены не назначены
                            @else
                                Нет назначенных цен
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Модальное окно редактирования цены -->
<div class="modal fade" id="editPriceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editPriceForm">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Редактировать цену</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Мастер</label>
                        <select name="master_id" class="form-select" required>
                            @foreach($masters as $master)
                                <option value="{{ $master->id }}">
                                    {{ $master->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Услуга</label>
                        <select name="service_id" class="form-select" required>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Цена (₽)</label>
                        <input type="number"
                               step="0.01"
                               name="price"
                               class="form-control"
                               placeholder="Оставьте пустым для базовой цены">

                        <small class="text-muted">
                            Необязательно. Если не указано, используется базовая цена услуги.
                        </small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Отмена
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const editModal = document.getElementById('editPriceModal');

    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const masterId = button.getAttribute('data-master');
        const serviceId = button.getAttribute('data-service');
        const price = button.getAttribute('data-price');

        const form = document.getElementById('editPriceForm');

        form.action = '{{ url("/admin/prices") }}/' + id;
        form.querySelector('select[name="master_id"]').value = masterId;
        form.querySelector('select[name="service_id"]').value = serviceId;

        const priceInput = form.querySelector('input[name="price"]');
        priceInput.value = price ? price : '';
    });
</script>
@endpush
@endsection