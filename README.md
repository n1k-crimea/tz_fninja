# Short Link

Веб-приложение для создания коротких ссылок с отслеживанием переходов и личным
кабинетом на базе **Filament v3**.

Стек: **Laravel 11 · PHP 8.2 · MySQL 8 · Filament v3**, разворачивается через
**Docker Compose** (PHP-FPM + Nginx + MySQL).

## Возможности

- Регистрация и вход (страницы аутентификации Filament).
- Создание короткой ссылки из оригинального URL (короткий код генерируется
  автоматически).
- Редирект с короткой ссылки на оригинальный URL.
- Каждый переход фиксируется: IP-адрес и дата/время.
- Личный кабинет:
  - список своих ссылок с количеством кликов;
  - удаление ссылки;
  - просмотр статистики по каждой ссылке (список переходов + общее число кликов);
  - виджет с суммарной статистикой на дашборде.
- Каждый пользователь видит только свои ссылки.

## Быстрый старт (Docker)

Требуется установленный Docker с Docker Compose v2.

```bash
cd short_link

# 1. Подготовить .env (уже настроен на MySQL и сервисы compose)
cp .env.example .env

# 2. Собрать и запустить контейнеры
docker compose up -d --build

# 3. Сгенерировать ключ приложения и накатить миграции
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

Приложение будет доступно на **http://localhost:8090**.

- `/` — редирект в личный кабинет.
- `/admin` — личный кабинет (login / register).
- `/{code}` — короткая ссылка (редирект + фиксация перехода).

> Порт можно изменить переменной `APP_PORT` в `.env`.
> Если хост-пользователь не `1000:1000`, пересоберите с
> `UID=$(id -u) GID=$(id -g) docker compose up -d --build`.

## Структура

| Компонент | Файл |
|-----------|------|
| Модели | `app/Models/Link.php`, `app/Models/Click.php` |
| Миграции | `database/migrations/*_create_links_table.php`, `*_create_clicks_table.php` |
| Редирект + учёт переходов | `app/Http/Controllers/RedirectController.php`, `routes/web.php` |
| Ресурс кабинета | `app/Filament/Resources/LinkResource.php` |
| Статистика переходов | `app/Filament/Resources/LinkResource/RelationManagers/ClicksRelationManager.php` |
| Виджет дашборда | `app/Filament/Widgets/StatsOverview.php` |

## Тесты

Функциональные тесты (редирект, учёт кликов, 404, автогенерация кода) работают на
in-memory SQLite:

```bash
docker compose exec app php artisan test
```

## Локальный запуск без Docker

Нужны PHP 8.2 (с расширением `intl`), Composer и MySQL. Пропишите доступ к БД в
`.env` (`DB_HOST=127.0.0.1`), затем:

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```
