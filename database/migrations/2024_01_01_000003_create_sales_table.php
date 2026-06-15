<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $t) {
            $t->id();
            $t->string('g_number')->nullable()->index();
            $t->date('date')->index();
            $t->dateTime('last_change_date')->nullable();
            $t->string('supplier_article')->nullable();
            $t->string('tech_size')->nullable();
            $t->string('barcode')->nullable();
            $t->decimal('total_price', 12, 2)->nullable();
            $t->integer('discount_percent')->nullable();
            $t->boolean('is_supply')->nullable();
            $t->boolean('is_realization')->nullable();
            $t->decimal('promo_code_discount', 12, 2)->nullable();
            $t->string('warehouse_name')->nullable();
            $t->string('country_name')->nullable();
            $t->string('oblast_okrug_name')->nullable();
            $t->string('region_name')->nullable();
            $t->bigInteger('income_id')->nullable();
            $t->string('sale_id')->nullable()->index();
            $t->bigInteger('odid')->nullable();
            $t->decimal('spp', 12, 2)->nullable();
            $t->decimal('for_pay', 12, 2)->nullable();
            $t->decimal('finished_price', 12, 2)->nullable();
            $t->decimal('price_with_disc', 12, 2)->nullable();
            $t->bigInteger('nm_id')->nullable()->index();
            $t->string('subject')->nullable();
            $t->string('category')->nullable();
            $t->string('brand')->nullable();
            $t->string('is_storno')->nullable();
            $t->char('row_hash', 40)->unique();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
