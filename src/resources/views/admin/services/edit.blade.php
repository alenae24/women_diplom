@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Редактирование услуги: {{ $service->name }}</h2>
        <a href="{{ url('/admin/services') }}" class="btn btn-secondary">Назад к списку</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ url('/admin/services/'.$service->id) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                @if($service->image)
                    <div class="mb-3">
                        <label class="form-label">Текущее изображение</label><br>
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" style="height: 100px; width: auto;">
                    </div>
                @endif
                <div class="mb-3">
                    <label class="form-label">Новое изображение (оставьте пустым, если не хотите менять)</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Название услуги</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $service->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Длительность (минуты)</label>
                    <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration', $service->duration) }}" required>
                    @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Базовая цена (₽)</label>
                    <input type="number" step="0.01" name="base_price" class="form-control" value="{{ old('base_price', $service->base_price ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Описание</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $service->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary">Обновить</button>
                <a href="{{ url('/admin/services') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
</div>
@endsection