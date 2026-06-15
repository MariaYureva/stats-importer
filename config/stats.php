<?php

use App\Models\Income;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Stock;

return [

    'base_url'      => env('STATS_API_BASE_URL', 'http://109.73.206.144:6969'),
    'key'           => env('STATS_API_KEY'),
    'timeout'       => (int) env('STATS_API_TIMEOUT', 30),
    'retries'       => (int) env('STATS_API_RETRIES', 3),
    'page_limit'    => (int) env('STATS_API_PAGE_LIMIT', 500),
    'lookback_days' => (int) env('STATS_API_LOOKBACK_DAYS', 30),
    'max_pages'     => (int) env('STATS_API_MAX_PAGES', 10000),

    'entities' => [

        'incomes' => [
            'endpoint' => '/api/incomes',
            'model'    => Income::class,
            'date_to'  => true,
            'columns'  => [
                'income_id', 'number', 'date', 'last_change_date', 'supplier_article',
                'tech_size', 'barcode', 'quantity', 'total_price', 'date_close',
                'warehouse_name', 'nm_id',
            ],
        ],

        'orders' => [
            'endpoint' => '/api/orders',
            'model'    => Order::class,
            'date_to'  => true,
            'columns'  => [
                'g_number', 'date', 'last_change_date', 'supplier_article', 'tech_size',
                'barcode', 'total_price', 'discount_percent', 'warehouse_name', 'oblast',
                'income_id', 'odid', 'nm_id', 'subject', 'category', 'brand',
                'is_cancel', 'cancel_dt',
            ],
        ],

        'sales' => [
            'endpoint' => '/api/sales',
            'model'    => Sale::class,
            'date_to'  => true,
            'columns'  => [
                'g_number', 'date', 'last_change_date', 'supplier_article', 'tech_size',
                'barcode', 'total_price', 'discount_percent', 'is_supply', 'is_realization',
                'promo_code_discount', 'warehouse_name', 'country_name', 'oblast_okrug_name',
                'region_name', 'income_id', 'sale_id', 'odid', 'spp', 'for_pay',
                'finished_price', 'price_with_disc', 'nm_id', 'subject', 'category',
                'brand', 'is_storno',
            ],
        ],

        'stocks' => [
            'endpoint' => '/api/stocks',
            'model'    => Stock::class,
            'date_to'  => false,
            'columns'  => [
                'date', 'last_change_date', 'supplier_article', 'tech_size', 'barcode',
                'quantity', 'is_supply', 'is_realization', 'quantity_full', 'warehouse_name',
                'in_way_to_client', 'in_way_from_client', 'nm_id', 'subject', 'category',
                'brand', 'sc_code', 'price', 'discount',
            ],
        ],

    ],
];
