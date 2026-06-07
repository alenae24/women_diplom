@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Галерея работ</h2>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-success">+ Добавить фото</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Изображение</th>
                    <th>Мастер</th>
                    <th>Услуга</th>
                    <th>Описание</th>
                    <th>Дата добавления</th>
                    <th>Действия</th>
                </tr>
            </thead>

            <tbody>
                @forelse($galleries as $item)
                    <tr>
                        <td>{{ $item->id }}</td>

                        <td>
                            @if($item->image)
                                <img src="{{ asset($item->image) }}"
                                     alt="{{ $item->title ?? 'Фото работы' }}"
                                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                            @else
                                —
                            @endif
                        </td>

                        <td>{{ $item->master->name ?? '—' }}</td>
                        <td>{{ $item->service->name ?? '—' }}</td>
                        <td>{{ $item->title ?? '—' }}</td>
                        <td>{{ $item->created_at->format('d.m.Y H:i') }}</td>

                        <td>
                            <form action="{{ route('admin.gallery.destroy', $item) }}"
                                  method="POST"
                                  onsubmit="return confirm('Удалить фото?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Фотографий пока нет</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection