@extends('layouts.admin')

@section('content')
<style>
    .add-master-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        padding: 10px 16px;
        height: 38px;
        white-space: nowrap;
    }

    .masters-actions {
        white-space: nowrap;
    }

    .master-photo-preview {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }

    .master-bio-cell {
        max-width: 360px;
        white-space: normal;
        line-height: 1.4;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Мастера</h2>

        <a href="{{ route('admin.masters.create') }}" class="btn btn-success add-master-btn">
            + Добавить мастера
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Фото</th>
                    <th>Имя</th>
                    <th>Уровень</th>
                    <th>Специализация</th>
                    <th>Биография</th>
                    <th>Действия</th>
                </tr>
            </thead>

            <tbody>
                @forelse($masters as $master)
                    <tr>
                        <td>{{ $master->id }}</td>

                        <td>
                            @if($master->photo)
                                <img src="{{ asset($master->photo) }}"
                                     alt="{{ $master->name }}"
                                     class="master-photo-preview">
                            @else
                                —
                            @endif
                        </td>

                        <td>{{ $master->name }}</td>
                        <td>{{ $master->level_name }}</td>
                        <td>{{ $master->specialization_name ?? '—' }}</td>

                        <td class="master-bio-cell">
                            {{ $master->bio ?: '—' }}
                        </td>

                        <td class="masters-actions">
                            <a href="{{ route('admin.masters.edit', $master) }}" class="btn btn-sm btn-warning">
                                ✎
                            </a>

                            <form action="{{ route('admin.masters.destroy', $master) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Удалить мастера?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger">
                                    ✘
                                </button>
                            </form>

                            <a href="{{ route('admin.schedule.calendar', $master->id) }}" class="btn btn-sm btn-info">
                                Календарь
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Нет мастеров</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection