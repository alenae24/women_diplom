@extends('layouts.app')

@section('content')
<style>
    .service-card {
        height: 100%;
        min-height: 520px;
        background-color: #ECF9FC;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: none;
        display: flex;
        flex-direction: column;
    }

    .service-card-img {
        width: 100%;
        height: 260px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .service-card-body {
        padding: 19px 22px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .service-card-title {
        min-height: 54px;
        color: #000000;
        font-size: 20px;
        line-height: 1.25;
        margin-bottom: 10px;
    }

    .service-card-description {
        min-height: 78px;
        max-height: 78px;
        overflow: hidden;
        font-size: 14px;
        color: #717171;
        line-height: 1.4;
        margin-bottom: 12px;
    }

    .service-card-price {
        min-height: 28px;
        margin-bottom: 8px;
    }

    .master-card {
        height: 100%;
        min-height: 360px;
    }

    .master-photo {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #00677B;
        margin: 0 auto 12px auto;
    }

    .gallery-card {
        position: relative;
        height: 260px;
        border-radius: 12px;
        overflow: hidden;
        background-color: #ECF9FC;
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
        padding: 12px 16px;
        background: rgba(0, 103, 123, 0.85);
        color: #ffffff;
        font-size: 14px;
    }

    .advantage-card {
        border: 1px solid #00677B;
        background-color: #ECF9FC;
        border-radius: 16px;
        padding: 32px 25px;
        min-height: 250px;
    }

    .advantage-icon {
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        align-self: flex-start;
    }

    .modal-photo {
        height: 260px;
        object-fit: cover;
        border-radius: 12px;
    }

    .clickable-card {
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .clickable-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0, 103, 123, 0.15) !important;
    }

    .hero-section {
        padding-top: 85px;
        padding-bottom: 130px;
    }

    .advantages-section {
        margin-top: 20px;
        margin-bottom: 130px;
    }

    .services-section {
        margin-top: 120px;
        margin-bottom: 140px;
    }

    .masters-section {
        margin-top: 130px;
        margin-bottom: 140px;
        padding-top: 75px;
        padding-bottom: 75px;
        background-color: #ECF9FC;
        border-radius: 30px;
    }

    .gallery-section {
        margin-top: 130px;
        margin-bottom: 140px;
    }

    .contacts-section {
        margin-top: 130px;
        margin-bottom: 90px;
    }

    .home-section-title {
        color: #00677B;
        margin-bottom: 48px !important;
        font-size: 34px;
        line-height: 1.2;
    }

    .hero-title {
        color: #00677B;
        font-family: 'Gogol', 'Montserrat Alternates', sans-serif;
        font-size: 40px;
        line-height: 1.1;
        font-weight: 400;
        margin-bottom: 28px;
    }

    .hero-text {
        font-family: 'Montserrat Alternates', sans-serif;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 24px;
    }

    @media (max-width: 768px) {
        .hero-section {
            padding-top: 45px;
            padding-bottom: 80px;
        }

        .advantages-section,
        .services-section,
        .masters-section,
        .gallery-section,
        .contacts-section {
            margin-top: 75px;
            margin-bottom: 85px;
        }

        .masters-section {
            padding-top: 55px;
            padding-bottom: 55px;
            border-radius: 22px;
        }

        .home-section-title {
            margin-bottom: 32px !important;
            font-size: 30px;
        }

        .hero-title {
            font-size: 34px;
        }
    }
    
    .contact-info-card,
    .contact-map-card {
        height: 100%;
        min-height: 400px;
    }
    
    .contact-map-card iframe {
        width: 100%;
        height: 100%;
        min-height: 400px;
        border: 0;
    }
    
    .contacts-section .row {
        align-items: stretch;
    }
    
    .contacts-section .col-lg-5,
    .contacts-section .col-lg-7 {
        display: flex;
    }
    
    .social-max-icon {
        width: 32px;
        height: 32px;
        border: 2px solid #00677B;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 700;
        color: #00677B;
        line-height: 1;
    }
</style>

<!-- Hero-блок с текстом и каруселью -->
<div class="container hero-section">
    <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <h1 class="hero-title">
                Безупречный маникюр для совершённого образа
            </h1>

            <p class="lead mt-3 hero-text">
                Доверьте свой образ профессионалам. Стильные решения, идеальный уход и внимание к деталям — все для того, чтобы вы чувствовали себя великолепно. Запишитесь сегодня и откройте для себя красоту на кончиках пальцев.
            </p>

            @guest
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg mt-2">Записаться онлайн</a>
                <a href="#services" class="btn btn-outline-secondary btn-lg mt-2 ms-2">Узнать стоимость услуг</a>
            @else
                <a href="{{ route('client.booking') }}" class="btn btn-primary btn-lg mt-2">Записаться онлайн</a>
                <a href="#services" class="btn btn-outline-secondary btn-lg mt-2 ms-2">Наши услуги</a>
            @endguest
        </div>

        <div class="col-lg-6">
            <div id="heroCarousel" class="carousel slide rounded-4 overflow-hidden" data-bs-ride="carousel" style="box-shadow: 0 0.5rem 1rem rgba(0, 103, 123, 0.1);">
                <div class="carousel-indicators">
                    @foreach(range(1, 3) as $i)
                        <button type="button"
                                data-bs-target="#heroCarousel"
                                data-bs-slide-to="{{ $i - 1 }}"
                                @if($i == 1) class="active" @endif
                                aria-label="Slide {{ $i }}">
                        </button>
                    @endforeach
                </div>

                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/1.jpg') }}" class="d-block w-100" alt="Маникюр" style="height: 400px; object-fit: cover;">
                    </div>

                    <div class="carousel-item">
                        <img src="{{ asset('images/7.jpg') }}" class="d-block w-100" alt="Педикюр" style="height: 400px; object-fit: cover;">
                    </div>

                    <div class="carousel-item">
                        <img src="{{ asset('images/4.jpg') }}" class="d-block w-100" alt="Мастера" style="height: 400px; object-fit: cover;">
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Предыдущий</span>
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Следующий</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Преимущества -->
<div class="container advantages-section">
    <h2 class="text-center gogol-title home-section-title">Наши преимущества</h2>

    <div class="row g-4">
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 advantage-card">
                <div class="d-flex gap-3">
                    <img src="{{ asset('images/preim1.svg') }}" alt="Иконка" class="advantage-icon">
                    <div>
                        <h5 style="color: #000000; font-weight: 600; margin-bottom: 8px; font-size: 16px;">
                            Высококвалифицированные мастера
                        </h5>
                        <p style="color: #717171; font-size: 14px; line-height: 1.4; margin-bottom: 0;">
                            Наши мастера имеют многолетний опыт и регулярно проходят обучение, чтобы быть в курсе последних трендов и технологий. Мы гарантируем качество работы благодаря профессионализму нашей команды.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100 advantage-card">
                <div class="d-flex gap-3">
                    <img src="{{ asset('images/preim1.svg') }}" alt="Иконка" class="advantage-icon">
                    <div>
                        <h5 style="color: #000000; font-weight: 600; margin-bottom: 8px; font-size: 16px;">
                            Гарантия безопасности и стерильности
                        </h5>
                        <p style="color: #717171; font-size: 14px; line-height: 1.4; margin-bottom: 0;">
                            Мы строго соблюдаем санитарные нормы: используем одноразовые инструменты и проводим тщательную стерилизацию многоразовых инструментов после каждого клиента. Все материалы сертифицированы и соответствуют стандартам качества.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100 advantage-card">
                <div class="d-flex gap-3">
                    <img src="{{ asset('images/preim1.svg') }}" alt="Иконка" class="advantage-icon">
                    <div>
                        <h5 style="color: #000000; font-weight: 600; margin-bottom: 8px; font-size: 16px;">
                            Онлайн-запись и оперативная обратная связь
                        </h5>
                        <p style="color: #717171; font-size: 14px; line-height: 1.4; margin-bottom: 0;">
                            Мы предлагаем удобную онлайн-запись через сайт. Вы можете выбрать удобное время и мастера всего за пару кликов. Мы готовы ответить на вопросы по телефону или в мессенджерах.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Услуги -->
<div class="container services-section" id="services">
    <h2 class="text-center gogol-title home-section-title">Стоимость услуг</h2>

    @if($services->count())
        <div id="servicesCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($services->chunk(3) as $index => $chunk)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="row g-4">
                            @foreach($chunk as $service)
                                <div class="col-md-4">
                                    <div class="card service-card border-0 clickable-card"
                                         data-modal-target="#serviceModal{{ $service->id }}">
                                        @if($service->image)
                                            <img src="{{ asset($service->image) }}"
                                                 class="service-card-img"
                                                 alt="{{ $service->name }}">
                                        @else
                                            <div class="service-card-img d-flex align-items-center justify-content-center" style="background-color: #dff3f7;">
                                                <i class="bi bi-image" style="font-size: 42px; color: #00677B;"></i>
                                            </div>
                                        @endif

                                        <div class="service-card-body">
                                            <h5 class="card-title service-card-title">
                                                {{ $service->name }}
                                            </h5>

                                            <p class="card-text service-card-description">
                                                {{ $service->description }}
                                            </p>

                                            <p class="card-text service-card-price">
                                                <strong>Цена:</strong> от {{ number_format($service->base_price, 0, ',', ' ') }} ₽
                                            </p>

                                            <button type="button"
                                                    class="btn btn-link p-0 mb-2 text-start"
                                                    style="color: #00677B; text-decoration: none;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#serviceModal{{ $service->id }}">
                                                Узнать подробнее…
                                            </button>

                                            <div class="mt-auto pt-3">
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
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#servicesCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Предыдущий</span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#servicesCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Следующий</span>
            </button>
        </div>
    @else
        <p class="text-center text-muted">Услуги пока не добавлены</p>
    @endif
</div>

<!-- Модальные окна услуг -->
@foreach($services as $service)
    <div class="modal fade" id="serviceModal{{ $service->id }}" tabindex="-1" aria-hidden="true">
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
                                     class="w-100 modal-photo">
                            @else
                                <div class="d-flex align-items-center justify-content-center"
                                     style="height: 260px; background-color: #ECF9FC; border-radius: 12px;">
                                    <i class="bi bi-image" style="font-size: 48px; color: #00677B;"></i>
                                </div>
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

<!-- Мастера -->
<div class="masters-section">
    <div class="container">
        <h2 class="text-center gogol-title home-section-title">Наши мастера</h2>

        <div class="row g-4 justify-content-center">
            @forelse($masters as $master)
                <div class="col-md-3 col-sm-6">
                    <div class="card master-card text-center border-0 shadow-sm clickable-card"
                         data-modal-target="#masterModal{{ $master->id }}">
                        <div class="card-body">
                            @if($master->photo)
                                <img src="{{ asset($master->photo) }}"
                                     alt="{{ $master->name }}"
                                     class="master-photo">
                            @else
                                <div class="master-photo d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-fill" style="font-size: 32px; color: #00677B;"></i>
                                </div>
                            @endif

                            <h5 class="card-title">{{ $master->name }}</h5>

                            <div class="mb-2">
                                <span class="badge" style="background-color: #00677B; color: white; font-size: 0.85rem; padding: 6px 12px;">
                                    {{ $master->specialization_name }} • {{ $master->level_name }}
                                </span>
                            </div>

                            @if($master->bio)
                                <p class="text-muted small mt-2 mb-0">
                                    {{ \Illuminate\Support\Str::limit($master->bio, 110) }}
                                </p>
                            @endif

                            <button type="button"
                                    class="btn btn-link p-0 mt-2"
                                    style="color: #00677B; text-decoration: none;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#masterModal{{ $master->id }}">
                                Узнать подробнее…
                            </button>

                            @if($master->servicePrices->count())
                                <div class="mt-3 text-start">
                                    <small class="text-muted">Услуги:</small>
                                    <ul class="list-unstyled small mt-1">
                                        @foreach($master->servicePrices as $mp)
                                            <li>
                                                ✔ {{ $mp->service->name }} —
                                                @if($mp->price)
                                                    {{ number_format($mp->price, 0, ',', ' ') }} ₽
                                                @else
                                                    {{ number_format($mp->service->base_price, 0, ',', ' ') }} ₽
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">Нет мастеров</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Модальные окна мастеров -->
@foreach($masters as $master)
    <div class="modal fade" id="masterModal{{ $master->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 18px; overflow: hidden;">
                <div class="modal-header" style="background-color: #ECF9FC;">
                    <h5 class="modal-title" style="color: #00677B;">
                        {{ $master->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-4 text-center">
                            @if($master->photo)
                                <img src="{{ asset($master->photo) }}"
                                     alt="{{ $master->name }}"
                                     style="width: 160px; height: 160px; object-fit: cover; border-radius: 50%; border: 4px solid #00677B;">
                            @else
                                <div class="d-flex align-items-center justify-content-center mx-auto"
                                     style="width: 160px; height: 160px; border-radius: 50%; border: 4px solid #00677B;">
                                    <i class="bi bi-person-fill" style="font-size: 60px; color: #00677B;"></i>
                                </div>
                            @endif

                            <h5 class="mt-3 mb-1">{{ $master->name }}</h5>

                            <span class="badge" style="background-color: #00677B; color: white;">
                                {{ $master->specialization_name }} • {{ $master->level_name }}
                            </span>
                        </div>

                        <div class="col-md-8">
                            <h6 style="color: #00677B;">О мастере</h6>

                            <p class="text-muted">
                                {{ $master->bio ?: 'Информация о мастере пока не добавлена.' }}
                            </p>

                            @if($master->servicePrices->count())
                                <h6 class="mt-4" style="color: #00677B;">Услуги и цены</h6>

                                <ul class="list-unstyled">
                                    @foreach($master->servicePrices as $mp)
                                        <li class="mb-2">
                                            <strong>{{ $mp->service->name }}</strong> —
                                            @if($mp->price)
                                                {{ number_format($mp->price, 0, ',', ' ') }} ₽
                                            @else
                                                {{ number_format($mp->service->base_price, 0, ',', ' ') }} ₽
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @auth
                                <a href="{{ route('client.booking') }}" class="btn btn-primary mt-2">
                                    Записаться к мастеру
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
@endforeach

<!-- Галерея работ -->
<div class="container gallery-section">
    <h2 class="text-center gogol-title home-section-title">Наши работы</h2>

    <div class="row g-4">
        @forelse($gallery as $item)
            <div class="col-md-4">
                <div class="gallery-card">
                    @if($item->image)
                        <img src="{{ asset($item->image) }}" alt="{{ $item->title ?? 'Работа мастера' }}">
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
        @empty
            <div class="col-12 text-center">
                <p class="text-muted">Фотографий пока нет</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Контакты -->
<div class="container contacts-section">
    <div class="row g-4 align-items-stretch">
        <div class="col-lg-5">
            <div class="card contact-info-card border-0 shadow-sm p-4 w-100" style="background-color: #ECF9FC; border-radius: 16px;">
                <h3 class="mb-4 gogol-title" style="color: #00677B;">Контакты</h3>

                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-telephone-fill fs-4 me-3" style="color: #00677B;"></i>
                    <div>
                        <p class="mb-0 fw-bold">Телефон</p>
                        <p class="mb-0">
                            <a href="tel:+78001234567" style="color: #000; text-decoration: none;">
                                +7 (800) 123-45-67
                            </a>
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-geo-alt-fill fs-4 me-3" style="color: #00677B;"></i>
                    <div>
                        <p class="mb-0 fw-bold">Адрес</p>
                        <p class="mb-0">
                            г. Великий Новгород, ул. Большая Московская, д. 130
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-envelope-fill fs-4 me-3" style="color: #00677B;"></i>
                    <div>
                        <p class="mb-0 fw-bold">Email</p>
                        <p class="mb-0">
                            <a href="mailto:admin@women-nail.ru" style="color: #000; text-decoration: none;">
                                admin@women-nail.ru
                            </a>
                        </p>
                    </div>
                </div>

                <div class="mt-auto">
                    <p class="fw-bold mb-2">Мы в соцсетях:</p>
                    <div class="d-flex gap-3 align-items-center">
                        <a href="#" class="text-decoration-none" style="color: #00677B;" title="ВКонтакте">
                            <i class="bi bi-vk fs-3"></i>
                        </a>
                    
                        <a href="#" class="text-decoration-none" title="MAX">
                            <span class="social-max-icon">MAX</span>
                        </a>
                    
                        <a href="#" class="text-decoration-none" style="color: #00677B;" title="Telegram">
                            <i class="bi bi-telegram fs-3"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card contact-map-card border-0 shadow-sm overflow-hidden w-100" style="border-radius: 16px;">
                <iframe
                    src="https://yandex.ru/map-widget/v1/?ll=31.311848%2C58.552197&z=16&pt=31.311848,58.552197,pm2rdm"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</div>
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