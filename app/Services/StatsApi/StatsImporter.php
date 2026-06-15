<?php

namespace App\Services\StatsApi;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class StatsImporter
{
    private string $ts;

    public function __construct(private StatsApiClient $client)
    {
    }

    /**
     * Выгрузить одну сущность за период и сохранить в БД.
     *
     * @param  callable|null  $onPage  function(int $page, int $rows): void — для логов
     * @return array{pages:int, fetched:int}
     */
    public function import(string $entity, string $dateFrom, ?string $dateTo, ?callable $onPage = null): array
    {
        DB::connection()->disableQueryLog();
        $this->ts = date("Y-m-d H:i:s");

        $config = config("stats.entities.$entity");

        if (! $config) {
            throw new InvalidArgumentException("Неизвестная сущность: $entity");
        }

        $limit    = (int) config('stats.page_limit', 500);
        $maxPages = (int) config('stats.max_pages', 10000);

        $query = ['dateFrom' => $dateFrom];
        if (! empty($config['date_to']) && $dateTo !== null) {
            $query['dateTo'] = $dateTo;
        }

        $model    = $config['model'];
        $columns  = $config['columns'];
        $pages    = 0;
        $fetched  = 0;
        $page     = 1;

        do {
            $result = $this->client->fetchPage($config['endpoint'], $query, $page, $limit);
            $rows   = $result['rows'];
            $count  = count($rows);

            if ($count > 0) {
                $prepared = array_map(fn ($row) => $this->normalize($row, $columns), $rows);
                $model::upsert($prepared, ['row_hash'], $this->updatableColumns($columns));
                $fetched += $count;
            }

            $pages++;

            if ($onPage) {
                $onPage($page, $count);
            }

            $lastPage = $result['lastPage'];
            $hasMore  = $count >= $limit
                && ($lastPage === null || $page < $lastPage)
                && $page < $maxPages;

            $page++;

            if ($hasMore) {
                usleep(200_000);
            }
        } while ($hasMore);

        return ['pages' => $pages, 'fetched' => $fetched];
    }

    /**
     * Привести строку ответа к набору колонок таблицы + row_hash.
     */
    private function normalize(array $row, array $columns): array
    {
        $attributes = [];

        foreach ($columns as $col) {
            $attributes[$col] = $this->pick($row, $col);
        }

        $attributes['row_hash']   = sha1(json_encode($attributes, JSON_UNESCAPED_UNICODE));
        $attributes['created_at'] = $this->ts;
        $attributes['updated_at'] = $this->ts;

        return $attributes;
    }

    /**
     * Достать значение по snake_case-ключу, а если нет — по camelCase-варианту.
     */
    private function pick(array $row, string $key): mixed
    {
        if (array_key_exists($key, $row)) {
            return $row[$key];
        }

        $camel = Str::camel($key);

        return $row[$camel] ?? null;
    }

    /**
     * Какие колонки обновлять при конфликте row_hash (всё, кроме хэша и created_at).
     */
    private function updatableColumns(array $columns): array
    {
        return array_merge($columns, ['updated_at']);
    }
}
