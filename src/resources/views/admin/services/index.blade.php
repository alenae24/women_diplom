@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Услуги</h2>
        <a href="{{ url('/admin/services/create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Добавить услугу</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark"><tr><th>ID</th><th>Изображение</th><th>Название</th><th>Длительность (мин)</th><th>Цена</th><th>Описание</th><th>Действия</th></tr></thead>
            <tbody>
                @forelse($services as $service)
                <tr>
                    <td>{{ $service->id }}</td>
                    <td>
                       @if($service->image)
                            <img src="{{ asset($service->image) }}"
                                 alt="{{ $service->name }}"
                                 width="70"
                                 height="50"
                                 style="object-fit: cover; border-radius: 4px;">
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $service->name }}</td>
                    <td>{{ $service->duration }}</td>
                    <td>{{ $service->base_price }}</td>
                    <td>{{ $service->description ?? '—' }}</td>
                    <td>
                        <a href="{{ url('/admin/services/'.$service->id.'/edit') }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <form action="{{ url('/admin/services/'.$service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить услугу?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Нет услуг</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection