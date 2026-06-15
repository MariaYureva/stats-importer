# Stats Importer

Выгрузка данных из тестового стат-API (продажи, заказы, склады, доходы) в MySQL.
Стек: Laravel 8, PHP 8.1, MySQL 8, Docker (nginx + php-fpm + mysql).

## Запуск

1. Скопировать окружение и указать ключ API:

```bash
   cp .env.example .env
   # в .env заполнить STATS_API_KEY и параметры БД
```

2. Поднять контейнеры:

```bash
   docker compose up -d --build
```

3. Установить зависимости и прогнать миграции:

```bash
   docker compose exec app composer install
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate
```

Веб доступен на http://localhost:8080

## Выгрузка данных

Команда `stats:sync` загружает данные за период и пишет в БД.
Поддерживает идемпотентность: повторный запуск не плодит дубли (дедупликация по `row_hash`).

```bash
# одна сущность за период
docker compose exec app php -d memory_limit=512M artisan stats:sync orders --from=2025-01-01 --to=2025-12-31

# все сразу
docker compose exec app php -d memory_limit=512M artisan stats:sync all --from=2025-01-01 --to=2025-12-31
```

Параметры:
- `entity` - `incomes` | `orders` | `sales` | `stocks` | `all` (по умолчанию `all`)
- `--from` - дата начала (Y-m-d); по умолчанию сегодня минус `STATS_API_LOOKBACK_DAYS`
- `--to` - дата конца (Y-m-d); по умолчанию сегодня

Склады (`stocks`) выгружаются только за текущий день (ограничение API).

## Расписание

В `app/Console/Kernel.php` настроен автоматический запуск (заказы и продажи раз в час,
доходы раз в сутки, склады раз в 3 часа). Для работы планировщика на сервере
добавить в cron: `* * * * * php artisan schedule:run`.

## Таблицы

- `incomes` - доходы (поставки)
- `orders` - заказы
- `sales` - продажи
- `stocks` - склады

В каждой таблице есть служебное поле `row_hash` (sha1 содержимого строки)
с уникальным индексом, основа дедупликации.

## Архитектура

- `app/Services/StatsApi/StatsApiClient.php` - HTTP-клиент к API: ключ, пагинация,
  retry, обработка rate limit (429).
- `app/Services/StatsApi/StatsImporter.php` - выгрузка по страницам, нормализация
  строк, расчёт `row_hash`, батч-upsert.
- `app/Console/Commands/SyncStatsCommand.php` - artisan-команда `stats:sync`.
- `config/stats.php` - конфигурация API и описание сущностей (эндпоинты, колонки).

## Развёрнутая БД

БД с выгруженными данными развёрнута на бесплатном хостинге Aiven (MySQL 8).
Доступы к ней переданы отдельно.
Подключение требует SSL (`ssl-mode=REQUIRED`).
