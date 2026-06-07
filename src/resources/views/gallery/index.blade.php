@extends('layouts.app')

@section('content')
<style>
    .gallery-card {
        position: relative;
        height: 280px;
        border-radius: 16px;
        overflow: hidden;
        background-color: #ECF9FC;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .gallery-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0, 103, 123, 0.15);
    }

    .gallery-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .gallery-overlay {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 14px 16px;
        background: rgba(0, 103, 123, 0.85);
        color: #fff;
        font-size: 14px;
    }

    .gallery-modal-img {
        width: 100%;
        height: 360px;
        object-fit: cover;
        border-radius: 12px;
    }

    .pagination {
        justify-content: center;
        margin-top: 20px;
    }

    .page-item.active .page-link {
        background-color: #00677B;
        border-color: #00677B;
        color: #ffffff;
    }

    .page-link {
        color: #00677B;
    }

    .page-link:hover {
        color: #005566;
    }
</style>

<div class="container my-5">
    <h1 class="text-center mb-4 gogol-title" style="color: #00677B;">
        Наши работы
    </h1>

    <form method="GET" action="{{ route('gallery.index') }}" class="row justify-content-center mb-5">
        <div class="col-md-4">
            <select name="master_id" class="form-select" onchange="this.form.submit()">
                <option value="">Все мастера</option>

                @foreach($masters as $master)
                    <option value="{{ $master->id }}" {{ request('master_id') == $master->id ? 'selected' : '' }}>
                        {{ $master->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="row g-4" id="galleryGrid">
        @include('gallery._grid', ['galleries' => $galleries])
    </div>

    @if($galleries->hasPages())
        <div class="mt-5">
            <p class="text-center text-muted small mb-3">
                Показано
                <strong>{{ $galleries->firstItem() }}</strong>–<strong>{{ $galleries->lastItem() }}</strong>
                из
                <strong>{{ $galleries->total() }}</strong>
                работ
            </p>

            <nav>
                <ul class="pagination justify-content-center">
                    @if($galleries->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Назад</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $galleries->previousPageUrl() }}">Назад</a>
                        </li>
                    @endif

                    @foreach($galleries->getUrlRange(1, $galleries->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $galleries->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if($galleries->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $galleries->nextPageUrl() }}">Вперёд</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Вперёд</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('click', function(event) {
        const card = event.target.closest('.gallery-card');

        if (!card) {
            return;
        }

        const modalTarget = card.dataset.modalTarget;

        if (modalTarget) {
            const modalElement = document.querySelector(modalTarget);

            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        }
    });
</script>
@endpush