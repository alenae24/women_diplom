@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <h2>Пользователи</h2>
        <a href="{{ url('/admin/users/create') }}" class="btn btn-success"><i class="bi bi-person-plus"></i> Добавить</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark"><tr><th>ID</th><th>Имя</th><th>Email</th><th>Телефон</th><th>Роль</th><th>Действия</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '—' }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <a href="{{ url('/admin/users/'.$user->id.'/edit') }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ url('/admin/users/'.$user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить пользователя?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6">Нет пользователей</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection