@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Добавить фото в галерею</h2>
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">Назад к списку</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Мастер</label>
                    <select name="master_id" class="form-select @error('master_id') is-invalid @enderror" required>
                        <option value="">-- Выберите мастера --</option>

                        @foreach($masters as $master)
                            <option value="{{ $master->id }}" @selected(old('master_id') == $master->id)>
                                {{ $master->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('master_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Услуга</label>
                    <select name="service_id" class="form-select @error('service_id') is-invalid @enderror">
                        <option value="">-- Без привязки к услуге --</option>

                        @foreach($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('service_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Изображение</label>
                    <input type="file"
                           name="image"
                           class="form-control @error('image') is-invalid @enderror"
                           accept="image/*"
                           required>

                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <small class="text-muted">
                        Допустимые форматы: jpg, jpeg, png, webp. Максимальный размер: 4 МБ.
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Описание</label>
                    <input type="text"
                           name="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}"
                           placeholder="Например: Маникюр с дизайном">

                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
</div>
@endsection