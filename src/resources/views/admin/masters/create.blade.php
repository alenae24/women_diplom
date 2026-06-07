@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Добавить мастера</h2>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.masters.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3"><label>Имя</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-3">
                    <label>Уровень</label>
                    <select name="level" class="form-select">
                        <option value="junior">Мастер</option>
                        <option value="middle">Топ-мастер</option>
                        <option value="top">Топ-про мастер</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Специализация</label>
                    <select name="specialization" class="form-select">
                        <option value="manicure">Маникюр</option>
                        <option value="pedicure">Педикюр</option>
                        <option value="universal">Универсал</option>
                    </select>
                </div>
                <div class="mb-3"><label>Биография</label><textarea name="bio" class="form-control" rows="3"></textarea></div>
                <div class="mb-3"><label>Фото</label><input type="file" name="photo" class="form-control" accept="image/*"></div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('admin.masters.index') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
</div>
@endsection