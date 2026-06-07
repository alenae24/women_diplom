@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Редактирование мастера: {{ $master->name }}</h2>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.masters.update', $master) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                @if($master->photo)
                    <div class="mb-3">
                        <label class="form-label">Текущее фото</label><br>
                    
                        @if($master->photo)
                            <img src="{{ asset($master->photo) }}"
                                 alt="{{ $master->name }}"
                                 width="80"
                                 height="80"
                                 style="object-fit: cover; border-radius: 6px;">
                        @else
                            <p class="text-muted">Фото не загружено</p>
                        @endif
                    </div>
                @endif
                <div class="mb-3"><label>Новое фото (оставьте пустым, если не менять)</label><input type="file" name="photo" class="form-control" accept="image/*"></div>
                <div class="mb-3"><label>Имя</label><input type="text" name="name" value="{{ old('name', $master->name) }}" class="form-control" required></div>
                <div class="mb-3">
                    <label>Уровень</label>
                    <select name="level" class="form-select">
                        <option value="junior" @selected($master->level == 'junior')>Мастер</option>
                        <option value="middle" @selected($master->level == 'middle')>Топ-мастер</option>
                        <option value="top" @selected($master->level == 'top')>Топ-про мастер</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Специализация</label>
                    <select name="specialization" class="form-select">
                        <option value="manicure" @selected($master->specialization == 'manicure')>Маникюр</option>
                        <option value="pedicure" @selected($master->specialization == 'pedicure')>Педикюр</option>
                        <option value="universal" @selected($master->specialization == 'universal')>Универсал</option>
                    </select>
                </div>
                <div class="mb-3"><label>Биография</label><textarea name="bio" class="form-control" rows="3">{{ old('bio', $master->bio) }}</textarea></div>
                <button type="submit" class="btn btn-primary">Обновить</button>
                <a href="{{ route('admin.masters.index') }}" class="btn btn-secondary">Назад</a>
            </form>
        </div>
    </div>
</div>
@endsection