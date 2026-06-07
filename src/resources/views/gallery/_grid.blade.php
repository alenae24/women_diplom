@forelse($galleries as $item)
    <div class="col-md-4">
        <div class="gallery-card" data-modal-target="#galleryModal{{ $item->id }}">
            @if($item->image)
                <img src="{{ asset($item->image) }}" alt="{{ $item->title ?? 'Работа мастера' }}">
            @else
                <div class="d-flex align-items-center justify-content-center h-100">
                    <i class="bi bi-image" style="font-size: 48px; color: #00677B;"></i>
                </div>
            @endif

            <div class="gallery-overlay">
                <p class="mb-1">
                    <strong>Мастер:</strong> {{ $item->master->name ?? '—' }}
                </p>

                @if($item->service)
                    <p class="mb-1">
                        <strong>Услуга:</strong> {{ $item->service->name }}
                    </p>
                @endif

                @if($item->title)
                    <p class="mb-0">{{ $item->title }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="galleryModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 18px; overflow: hidden;">
                <div class="modal-header" style="background-color: #ECF9FC;">
                    <h5 class="modal-title" style="color: #00677B;">
                        {{ $item->title ?? 'Работа мастера' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4 align-items-start">
                        <div class="col-md-7">
                            @if($item->image)
                                <img src="{{ asset($item->image) }}"
                                     alt="{{ $item->title ?? 'Работа мастера' }}"
                                     class="gallery-modal-img">
                            @endif
                        </div>

                        <div class="col-md-5">
                            <p class="mb-2">
                                <strong>Мастер:</strong> {{ $item->master->name ?? '—' }}
                            </p>

                            @if($item->service)
                                <p class="mb-2">
                                    <strong>Услуга:</strong> {{ $item->service->name }}
                                </p>
                            @endif

                            @if($item->title)
                                <p class="text-muted mt-3">
                                    {{ $item->title }}
                                </p>
                            @endif

                            @auth
                                <a href="{{ route('client.booking') }}" class="btn btn-primary mt-2">
                                    Записаться
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="btn btn-primary mt-2">
                                    Зарегистрироваться для записи
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center">
        <p class="text-muted">Фотографий пока нет</p>
    </div>
@endforelse