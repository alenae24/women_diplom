<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --admin-primary: #00677B;
            --admin-primary-dark: #005566;
            --admin-primary-light: #ECF9FC;
            --admin-gray-bg: #F8F9FA;
        }

        body {
            background-color: var(--admin-gray-bg);
        }

        /* ----- Боковая навигация ----- */
        .sidebar {
            background-color: var(--admin-primary-dark) !important;
            min-height: 100vh;
            padding: 0;
        }
        .sidebar .nav-link,
        .sidebar a:not(.btn) {
            display: block;
            padding: 12px 20px;
            color: #e0f0f3;
            text-decoration: none;
            transition: all 0.2s;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: var(--admin-primary);
            color: white;
        }
        .sidebar i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .sidebar h4, .sidebar small {
            color: white;
        }

        /* ----- Верхняя панель ----- */
        .navbar-light.bg-light {
            background-color: white !important;
            border-bottom: 1px solid var(--admin-primary-light);
        }

        /* ----- Контент ----- */
        .content {
            padding: 20px;
        }

        /* ----- Кнопки ----- */
        .btn-primary {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
        }
        .btn-primary:hover {
            background-color: var(--admin-primary-dark);
            border-color: var(--admin-primary-dark);
        }
        .btn-outline-primary {
            color: var(--admin-primary);
            border-color: var(--admin-primary);
        }
        .btn-outline-primary:hover {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
            color: white;
        }

        /* ----- Карточки ----- */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .card-header {
            background-color: var(--admin-primary-light);
            color: var(--admin-primary-dark);
            border-bottom: 2px solid var(--admin-primary);
            font-weight: 600;
        }

        /* ----- Таблицы ----- */
        .table {
            border-radius: 12px;
            overflow: hidden;
        }
        .table thead th {
            background-color: var(--admin-primary);
            color: white;
            border-bottom: none;
        }
        .table-hover tbody tr:hover {
            background-color: var(--admin-primary-light);
        }
        /* Если используется класс .table-dark, переопределяем */
        .table-dark thead th {
            background-color: var(--admin-primary) !important;
            color: white;
        }

        /* ----- Пагинация ----- */
        .pagination .page-item.active .page-link {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
        }
        .pagination .page-link {
            color: var(--admin-primary);
        }
        .pagination .page-link:hover {
            color: var(--admin-primary-dark);
        }

        /* ----- Формы ----- */
        .form-control:focus, .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 0.2rem rgba(0,103,123,0.25);
        }

        /* ----- Модальные окна ----- */
        .modal-header {
            background-color: var(--admin-primary-light);
            color: var(--admin-primary-dark);
            border-bottom: 1px solid var(--admin-primary);
        }

        /* ----- Бэйдж ----- */
        .badge.bg-info {
            background-color: var(--admin-primary-light) !important;
            color: var(--admin-primary-dark) !important;
        }

        /* ----- Алёрты ----- */
        .alert-success {
            background-color: #d1e7dd;
            border-color: #badbcc;
            color: #0f5132;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c2c7;
            color: #842029;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 p-0 sidebar">
                <div class="text-center py-3">
                    <h4>Women Studio</h4>
                </div>
                <nav>
                    <a href="{{ url('/admin') }}"><i class="bi bi-speedometer2"></i> Дашборд</a>
                    <a href="{{ url('/admin/masters') }}"><i class="bi bi-person-badge"></i> Мастера</a>
                    <a href="{{ route('admin.schedule.index') }}" class="nav-link"><i class="bi bi-calendar-week"></i>Расписание мастеров</a>
                    <a href="{{ url('/admin/services') }}"><i class="bi bi-scissors"></i> Услуги</a>
                    <a href="{{ url('/admin/users') }}"><i class="bi bi-people"></i> Пользователи</a>
                    <a href="{{ url('/admin/prices') }}"><i class="bi bi-currency-ruble"></i> Цены</a>
                    <a href="{{ url('/admin/appointments') }}"><i class="bi bi-calendar-check"></i> Записи</a>
                    <a href="{{ route('admin.gallery.index') }}"><i class="bi bi-images"></i> Галерея</a>
                    <a href="{{ url('/') }}"><i class="bi bi-house-door"></i> На сайт</a>
                </nav>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-0">
                <nav class="navbar navbar-light bg-light border-bottom">
                    <div class="container-fluid">
                        <span class="navbar-text">Добро пожаловать, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Выйти</button>
                        </form>
                    </div>
                </nav>
                <div class="content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>