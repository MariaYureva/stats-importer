<?php

namespace App\Console\Commands;

use App\Services\StatsApi\StatsImporter;
use Illuminate\Console\Command;
use Throwable;

class SyncStatsCommand extends Command
{
    protected $signature = 'stats:sync
                            {entity=all : incomes|orders|sales|stocks|all}
                            {--from= : Дата ОТ (Y-m-d), по умолчанию сегодня минус lookback_days}
                            {--to=   : Дата ДО (Y-m-d), по умолчанию сегодня}';

    protected $description = 'Выгрузка данных из тестового стат-API в БД';

    public function handle(StatsImporter $importer): int
    {
        $entity = $this->argument('entity');
        $known  = array_keys(config('stats.entities'));

        $targets = $entity === 'all' ? $known : [$entity];

        foreach ($targets as $target) {
            if (! in_array($target, $known, true)) {
                $this->error("Неизвестная сущность: $target. Доступны: " . implode(', ', $known) . ', all');
                return self::FAILURE;
            }
        }

        $lookback = (int) config('stats.lookback_days', 30);
        $dateTo   = $this->option('to') ?: now()->format('Y-m-d');
        $dateFrom = $this->option('from') ?: now()->subDays($lookback)->format('Y-m-d');

        foreach ($targets as $target) {
            $from = $target === 'stocks' ? now()->format('Y-m-d') : $dateFrom;
            $to   = $target === 'stocks' ? null : $dateTo;

            $this->info(sprintf(
                'Выгрузка [%s] с %s%s ...',
                $target, $from, $to ? " по $to" : ' (только текущий день)'
            ));

            try {
                $result = $importer->import($target, $from, $to, function (int $page, int $rows) {
                    $this->line("  страница $page: получено строк — $rows");
                });

                $this->info(sprintf(
                    "  готово: страниц %d, строк обработано %d\n",
                    $result['pages'], $result['fetched']
                ));
            } catch (Throwable $e) {
                $this->error('  ошибка: ' . $e->getMessage());
                return self::FAILURE;
            }
        }

        $this->info('Выгрузка завершена.');
        return self::SUCCESS;
    }
}
