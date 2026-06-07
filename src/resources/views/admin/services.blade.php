@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Услуги и цены мастеров</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-secondary mb-3">← Назад</a>

    <div class="card mb-4">
        <div class="card-header">Установить цену мастеру на услугу</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.price.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label>Мастер</label>
                        <select name="master_id" class="form-select" required>
                            <option value="">-- Выберите --</option>
                            @foreach($masters as $master)
                                <option value="{{ $master->id }}">{{ $master->name }} ({{ $master->level }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Услуга</label>
                        <select name="service_id" class="form-select" required>
                            <option value="">-- Выберите --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Цена (₽)</label>
                        <input type="number" name="price" step="0.01" class="form-control" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection