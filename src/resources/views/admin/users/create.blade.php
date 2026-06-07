@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <h2>Добавить пользователя</h2>
        <a href="{{ url('/admin/users') }}" class="btn btn-secondary">Назад</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ url('/admin/users') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Имя</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Телефон</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Пароль</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Подтверждение пароля</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Роль</label>
                    <select name="role" class="form-select">
                        <option value="client">Клиент</option>
                        <option value="admin">Администратор</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Создать</button>
                <a href="{{ url('/admin/users') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
</div>
@endsection