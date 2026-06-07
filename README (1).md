# Women Studio — сайт студии маникюра с онлайн-записью

## О проекте

Этот проект я разработала в рамках выпускной квалификационной работы.  

**Тема:** «Разработка сайта для студии маникюра “WOMEN” с возможностью онлайн-записи клиентов».

Сайт позволяет клиентам посмотреть услуги, мастеров, галерею работ и записаться онлайн.  
Также в проекте есть административная панель, через которую можно управлять мастерами, услугами, ценами, расписанием, записями, пользователями и галереей.

Опубликованная версия сайта доступна по адресу:

```text
https://women-nail.ru/
```

## Используемые технологии

- PHP 8.2
- Laravel
- MySQL 8.0
- Nginx
- Docker
- Docker Compose
- phpMyAdmin
- Blade
- Bootstrap 5
- JavaScript / AJAX

---

## Структура проекта

```text
women-main/
├── docker/
│   ├── nginx/
│   │   └── default.conf
│   └── php/
│       └── Dockerfile
├── src/
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── artisan
│   ├── composer.json
│   └── .env.example
├── docker-compose.yml
└── README.md
```

Laravel-проект находится в папке `src`.

---

## Локальный запуск проекта

### 1. Склонировать репозиторий

```bash
git clone <ссылка-на-репозиторий>
cd women-main
```

### 2. Создать файл окружения

Файл `.env` должен находиться внутри папки `src`.

```bash
cp src/.env.example src/.env
```

Если файла `.env.example` нет, можно создать `src/.env` вручную и указать настройки из раздела ниже.

### 3. Настроить `.env` для локального запуска

Для локального запуска через Docker в `src/.env` должны быть такие основные настройки:

```env
APP_NAME="WOMEN STUDIO"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080

APP_LOCALE=ru
APP_FALLBACK_LOCALE=ru
APP_FAKER_LOCALE=ru_RU

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=women_studio
DB_USERNAME=root
DB_PASSWORD=root

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

MAIL_MAILER=log
MAIL_FROM_ADDRESS=admin@women-nail.ru
MAIL_FROM_NAME="Women Studio"
```

Важно: для Docker нужно указывать именно:

```env
DB_HOST=mysql
```

а не `localhost`, потому что база данных запущена в отдельном контейнере.

### 4. Запустить контейнеры

```bash
docker-compose up -d --build
```

После запуска будут подняты контейнеры:

- `nginx` — веб-сервер;
- `php` — PHP-контейнер с Composer;
- `mysql` — база данных;
- `phpmyadmin` — панель управления базой данных.

### 5. Установить зависимости

```bash
docker-compose exec php bash
composer install
```

### 6. Настроить Laravel

Внутри контейнера `php` нужно выполнить:

```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan optimize:clear
exit
```

Если уже выполнена команда:

```bash
docker-compose exec php bash
```

то следующие команды нужно вводить уже внутри контейнера, где строка начинается примерно так:

```text
root@...:/var/www/html#
```

---

## Как открыть сайт локально

После запуска проект будет доступен по адресу:

```text
http://localhost:8080
```

Административная панель локально:

```text
http://localhost:8080/admin
```

phpMyAdmin:

```text
http://localhost:8081
```

Данные для входа в phpMyAdmin:

```text
Сервер: mysql
Пользователь: root
Пароль: root
База данных: women_studio
```

Также MySQL доступен с компьютера на порту:

```text
3307
```

Данные для подключения из внешней программы:

```text
host: 127.0.0.1
port: 3307
user: root
password: root
database: women_studio
```

---

## Как открыть опубликованный сайт

Публичная версия сайта:

```text
https://women-nail.ru/
```

Административная панель опубликованного сайта:

```text
https://women-nail.ru/admin
```

Для входа в административную панель нужен пользователь с ролью `admin`.

---

## Основной функционал

### Клиентская часть

- регистрация и авторизация;
- подтверждение email;
- просмотр услуг;
- просмотр галереи работ;
- просмотр информации о мастерах;
- онлайн-запись на услугу;
- выбор мастера, даты и свободного времени;
- личный кабинет клиента;
- просмотр предстоящих и прошедших записей;
- отмена записи;
- редактирование профиля.

### Административная панель

- управление пользователями;
- управление мастерами;
- загрузка фотографий мастеров;
- управление услугами;
- загрузка изображений услуг;
- назначение индивидуальных цен мастерам;
- управление расписанием мастеров;
- общий календарь расписания всех мастеров;
- управление записями клиентов;
- управление галереей работ;
- загрузка фотографий в галерею.

---

## Docker-конфигурация

В проекте используется `docker-compose.yml`:

```yaml
version: '3.8'

services:
  nginx:
    image: nginx:alpine
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php
      - mysql

  php:
    build:
      context: ./docker/php
    volumes:
      - ./src:/var/www/html
    depends_on:
      - mysql

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: women_studio
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "3307:3306"
    volumes:
      - mysql_data:/var/lib/mysql

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    ports:
      - "8081:80"
    environment:
      PMA_HOST: mysql
    depends_on:
      - mysql

volumes:
  mysql_data:
```

PHP-контейнер собирается из файла `docker/php/Dockerfile`:

```dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
```

---

## Безопасность

В проекте используются стандартные механизмы Laravel:

- CSRF-защита форм;
- хэширование паролей;
- валидация входящих данных;
- разграничение доступа по ролям;
- подтверждение email;
- экранирование данных в Blade-шаблонах.

---

## Автор

Проект выполнен в рамках выпускной квалификационной работы.

Разработчик: Егорова Алёна, группа 3994.
