<?php

namespace App\Services\StatsApi;

use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class StatsApiClient
{
    public function __construct(
        private string $baseUrl,
        private string $key,
        private int $timeout = 30,
        private int $retries = 3,
    ) {
    }

    public static function fromConfig(): self
    {
        return new self(
            rtrim((string) config('stats.base_url'), '/'),
            (string) config('stats.key'),
            (int) config('stats.timeout', 30),
            (int) config('stats.retries', 3),
        );
    }

    /**
     * Запрос одной страницы.
     *
     * @return array{rows: array<int, array>, lastPage: int|null, currentPage: int}
     */
    public function fetchPage(string $endpoint, array $query, int $page, int $limit): array
    {
        $url    = $this->baseUrl . $endpoint;
        $params = array_merge($query, [
            'page'  => $page,
            'limit' => $limit,
            'key'   => $this->key,
        ]);

        $response  = null;
        $lastError = 'unknown error';

        for ($attempt = 1; $attempt <= $this->retries; $attempt++) {
            try {
                $response = Http::timeout($this->timeout)->acceptJson()->get($url, $params);

                if ($response->successful()) {
                    break;
                }

                $lastError = "HTTP " . $response->status();

                if ($response->status() === 429) {
                    sleep(2 * $attempt);
                    continue;
                }
            } catch (Throwable $e) {
                $response  = null;
                $lastError = $e->getMessage();
            }

            if ($attempt < $this->retries) {
                usleep(300_000 * $attempt);
            }
        }

        if ($response === null || ! $response->successful()) {
            throw new RuntimeException(sprintf(
                'Запрос к %s (page %d) не удался: %s',
                $endpoint, $page, $lastError
            ));
        }

        $json = $response->json() ?? [];

        $rows = $json['data'] ?? (array_is_list($json) ? $json : []);

        $lastPage = $json['meta']['last_page'] ?? $json['last_page'] ?? null;
        $current  = $json['meta']['current_page'] ?? $json['current_page'] ?? $page;

        return [
            'rows'        => is_array($rows) ? $rows : [],
            'lastPage'    => $lastPage !== null ? (int) $lastPage : null,
            'currentPage' => (int) $current,
        ];
    }
}
