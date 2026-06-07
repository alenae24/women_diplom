@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <h2>Редактирование пользователя: {{ $user->name }}</h2>
        <a href="{{ url('/admin/users') }}" class="btn btn-secondary">Назад</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ url('/admin/users/'.$user->id) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Имя</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                    <small class="text-muted">Email нельзя изменить</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Телефон</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Новый пароль (оставьте пустым, если не меняете)</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Подтверждение пароля</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Роль</label>
                    <select name="role" class="form-select">
                        <option value="client" @selected($user->role == 'client')>Клиент</option>
                        <option value="admin" @selected($user->role == 'admin')>Администратор</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Обновить</button>
                <a href="{{ url('/admin/users') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
</div>
@endsection