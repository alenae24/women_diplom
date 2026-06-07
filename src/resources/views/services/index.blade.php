@extends('layouts.app')

@section('content')
<style>
    .service-page-card {
        height: 100%;
        min-height: 520px;
        background-color: #ECF9FC;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .service-page-img {
        width: 100%;
        height: 240px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .service-page-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .service-page-title {
        min-height: 52px;
        color: #000000;
        line-height: 1.25;
        margin-bottom: 10px;
    }

    .service-page-description {
        min-height: 78px;
        max-height: 78px;
        overflow: hidden;
        font-size: 14px;
        color: #717171;
        line-height: 1.4;
        margin-bottom: 12px;
    }

    .clickable-card {
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .clickable-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0, 103, 123, 0.15) !important;
    }

    .modal-service-img {
        width: 100%;
        height: 260px;
        object-fit: cover;
        border-radius: 12px;
    }
</style>

<div class="container my-5">
    <h1 class="text-center mb-4" style="color: #00677B;">Все услуги</h1>

    <div class="row g-4">
        @foreach($services as $service)
            <div class="col-md-4">
                <div class="card service-page-card border-0 shadow-sm clickable-card"
                     data-modal-target="#servicePageModal{{ $service->id }}">
                    @if($service->image)
                        <img src="{{ asset($service->image) }}"
                             class="service-page-img"
                             alt="{{ $service->name }}">
                    @else
                        <img src="{{ asset('images/service-default.jpg') }}"
                             class="service-page-img"
                             alt="{{ $service->name }}">
                    @endif

                    <div class="service-page-body">
                        <h5 class="card-title service-page-title">
                            {{ $service->name }}
                        </h5>

                        <p class="card-text text-muted service-page-description">
                            {{ $service->description ?? 'Описание отсутствует' }}
                        </p>

                        <p class="card-text mb-2">
                            <strong>Длительность:</strong> {{ $service->duration }} мин
                        </p>

                        <p class="card-text mb-3">
                            <strong>Цена:</strong> от {{ number_format($service->base_price, 0, ',', ' ') }} ₽
                        </p>

                        <button type="button"
                                class="btn btn-link p-0 mb-3 text-start"
                                style="color: #00677B; text-decoration: none;"
                                data-bs-toggle="modal"
                                data-bs-target="#servicePageModal{{ $service->id }}">
                            Узнать подробнее…
                        </button>

                        <div class="mt-auto">
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-primary w-100">Записаться</a>
                            @else
                                <a href="{{ route('client.booking') }}?service={{ $service->id }}" class="btn btn-primary w-100">Записаться</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@foreach($services as $service)
    <div class="modal fade" id="servicePageModal{{ $service->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 18px; overflow: hidden;">
                <div class="modal-header" style="background-color: #ECF9FC;">
                    <h5 class="modal-title" style="color: #00677B;">
                        {{ $service->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4 align-items-start">
                        <div class="col-md-5">
                            @if($service->image)
                                <img src="{{ asset($service->image) }}"
                                     alt="{{ $service->name }}"
                                     class="modal-service-img">
                            @else
                                <img src="{{ asset('images/service-default.jpg') }}"
                                     alt="{{ $service->name }}"
                                     class="modal-service-img">
                            @endif
                        </div>

                        <div class="col-md-7">
                            <p class="text-muted">
                                {{ $service->description ?? 'Описание услуги пока не добавлено.' }}
                            </p>

                            <p class="mb-2">
                                <strong>Длительность:</strong> {{ $service->duration }} мин.
                            </p>

                            <p class="mb-3">
                                <strong>Стоимость:</strong> от {{ number_format($service->base_price, 0, ',', ' ') }} ₽
                            </p>

                            @guest
                                <a href="{{ route('register') }}" class="btn btn-primary">
                                    Записаться
                                </a>
                            @else
                                <a href="{{ route('client.booking') }}?service={{ $service->id }}" class="btn btn-primary">
                                    Записаться на услугу
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.clickable-card').forEach(function (card) {
            card.addEventListener('click', function (event) {
                if (event.target.closest('a, button, input, select, textarea')) {
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
        });
    });
</script>
@endpush