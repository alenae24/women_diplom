<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Women Studio</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.svg') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        @font-face {
            font-family: 'Gogol';
            src: url('{{ asset('fonts/Gogol.otf') }}') format('opentype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        :root {
            --primary: #00677B;
            --primary-dark: #005566;
            --primary-light: #ECF9FC;
            --text-main: #1f1f1f;
            --text-muted: #717171;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Montserrat Alternates', sans-serif;
            color: var(--text-main);
            background-color: #ffffff;
            font-size: 14px;
        }

        main {
            flex: 1;
        }

        h1,
        h2,
        h3,
        .gogol-title,
        .section-title {
            font-family: 'Gogol', 'Montserrat Alternates', sans-serif;
        }

        .section-title {
            font-size: 34px;
            margin-bottom: 32px;
        }

        .navbar {
            background-color: transparent !important;
            overflow: visible;
        }

        .navbar .container {
            position: relative;
        }

        .navbar .container::after {
            content: '';
            position: absolute;
            right: 0;
            bottom: -15px;
            width: 925px;
            max-width: 75%;
            height: 1px;
            background-color: var(--primary);
            pointer-events: none;
        }

        .navbar-brand img {
            height: 50px;
        }

        .navbar-nav .nav-link {
            color: #333333;
            transition: color 0.2s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary) !important;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            border-radius: 6px;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            border-radius: 6px;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
        }

        .btn-link {
            color: var(--primary);
            text-decoration: none;
        }

        .btn-link:hover {
            color: var(--primary-dark);
        }

        a:not(.btn) {
            color: var(--primary);
            text-decoration: none;
        }

        a:not(.btn):hover {
            color: var(--primary-dark);
        }

        .btn-nav {
            margin-left: 0.5rem;
            padding: 0.375rem 1rem;
            border-radius: 30px;
            font-weight: 500;
        }

        .btn-outline-danger {
            border-radius: 30px;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
        }

        .site-card {
            background-color: var(--primary-light);
            border: none;
            border-radius: 12px;
            box-shadow: none;
        }

        .line-clamp-4 {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #servicesCarousel .card-body {
            padding: 19px 22px;
        }

        #servicesCarousel .carousel-control-prev-icon,
        #servicesCarousel .carousel-control-next-icon {
            background-color: var(--primary);
            border-radius: 50%;
            padding: 20px;
            background-size: 60% 60%;
        }

        #servicesCarousel .carousel-control-prev,
        #servicesCarousel .carousel-control-next {
            width: 5%;
            opacity: 0.8;
        }

        #servicesCarousel .carousel-control-prev:hover,
        #servicesCarousel .carousel-control-next:hover {
            opacity: 1;
        }

        .gallery-card {
            border-radius: 16px;
            overflow: hidden;
            background-color: var(--primary-light);
            box-shadow: 0 4px 6px rgba(0,0,0,0.08);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            height: 100%;
            position: relative;
        }

        .gallery-card img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .gallery-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 103, 123, 0.15);
        }

        .gallery-card:hover img {
            transform: scale(1.05);
        }

        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 103, 123, 0.9);
            color: #ffffff;
            padding: 15px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
            border-radius: 0 0 16px 16px;
        }

        .gallery-card:hover .gallery-overlay {
            transform: translateY(0);
        }

        .footer {
            margin-top: auto;
            background-color: var(--primary);
            border-top: 1px solid var(--primary);
        }

        .footer h5 {
            color: #ffffff;
            font-family: 'Montserrat Alternates', sans-serif;
            font-weight: 600;
        }

        .footer,
        .footer p,
        .footer li,
        .footer span {
            color: #f0f0f0;
        }

        .footer a {
            color: #f0f0f0 !important;
        }

        .footer a:hover {
            color: #ffffff !important;
        }

        @media (max-width: 768px) {
            .navbar .container::after {
                width: 50%;
            }

            .btn-nav {
                margin-left: 0;
                margin-top: 0.5rem;
                width: 100%;
            }

            .navbar-nav {
                align-items: stretch !important;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="Логотип WOMEN">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('services.index') }}">Услуги</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('gallery.index') }}">Галерея</a>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="btn btn-outline-primary btn-nav" href="{{ route('login') }}">Войти</a>
                        </li>

                        <li class="nav-item">
                            <a class="btn btn-primary btn-nav" href="{{ route('register') }}">Регистрация</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.profile.edit') }}">Профиль</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.dashboard') }}">Мои записи</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.booking') }}">Записаться</a>
                        </li>

                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Админка</a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-nav">Выйти</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger">{{ session('error') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer mt-auto py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="mb-3">Women Studio</h5>
                    <p class="small">
                        Студия маникюра «WOMEN» — профессиональный уход за вашими руками.
                        Уютная атмосфера, современные материалы, опытные мастера.
                    </p>
                    <p class="small mb-0">
                        Режим работы: <strong>ежедневно, 11:00–21:00</strong>
                    </p>
                </div>

                <div class="col-md-4">
                    <h5 class="mb-3">Разделы</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-decoration-none small">Главная</a></li>
                        <li><a href="{{ route('services.index') }}" class="text-decoration-none small">Услуги</a></li>
                        <li><a href="{{ route('gallery.index') }}" class="text-decoration-none small">Галерея</a></li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <h5 class="mb-3">Контакты</h5>

                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="bi bi-telephone-fill me-2"></i>
                            <a href="tel:+78001234567" class="text-decoration-none">+7 (800) 123-45-67</a>
                        </li>

                        <li class="mb-2">
                            <i class="bi bi-envelope-fill me-2"></i>
                            <a href="mailto:admin@women-nail.ru" class="text-decoration-none">admin@women-nail.ru</a>
                        </li>

                        <li class="mb-2">
                            <i class="bi bi-geo-alt-fill me-2"></i>
                            <span>г. Великий Новгород, ул. Большая Московская, д. 130</span>
                        </li>
                    </ul>

                    <div class="d-flex gap-3 mt-2 align-items-center">
                        <a href="#" class="text-decoration-none" style="color: #ffffff;" title="ВКонтакте">
                            <i class="bi bi-vk fs-5"></i>
                        </a>
                    
                        <a href="#" class="text-decoration-none" title="MAX">
                            <span style="
                                width: 22px;
                                height: 22px;
                                border: 1.5px solid #ffffff;
                                border-radius: 50%;
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 7px;
                                font-weight: 700;
                                color: #ffffff;
                                line-height: 1;
                            ">
                                MAX
                            </span>
                        </a>
                    
                        <a href="#" class="text-decoration-none" style="color: #ffffff;" title="Telegram">
                            <i class="bi bi-telegram fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <hr class="my-3" style="background-color: #ffffff; opacity: 0.3;">

            <div class="text-center small">
                &copy; {{ date('Y') }} Women Studio. Все права защищены.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.phone-mask').forEach(function (input) {
                input.addEventListener('input', function () {
                    let value = input.value.replace(/\D/g, '');

                    if (value.startsWith('8')) {
                        value = '7' + value.substring(1);
                    }

                    if (!value.startsWith('7')) {
                        value = '7' + value;
                    }

                    value = value.substring(0, 11);

                    let formatted = '+7';

                    if (value.length > 1) {
                        formatted += ' (' + value.substring(1, 4);
                    }

                    if (value.length >= 4) {
                        formatted += ') ' + value.substring(4, 7);
                    }

                    if (value.length >= 7) {
                        formatted += ' - ' + value.substring(7, 9);
                    }

                    if (value.length >= 9) {
                        formatted += ' - ' + value.substring(9, 11);
                    }

                    input.value = formatted;
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>