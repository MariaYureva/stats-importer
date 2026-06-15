<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $t) {
            $t->id();
            $t->date('date')->index();
            $t->dateTime('last_change_date')->nullable();
            $t->string('supplier_article')->nullable();
            $t->string('tech_size')->nullable();
            $t->string('barcode')->nullable();
            $t->integer('quantity')->default(0);
            $t->boolean('is_supply')->nullable();
            $t->boolean('is_realization')->nullable();
            $t->integer('quantity_full')->nullable();
            $t->string('warehouse_name')->nullable();
            $t->integer('in_way_to_client')->nullable();
            $t->integer('in_way_from_client')->nullable();
            $t->bigInteger('nm_id')->nullable()->index();
            $t->string('subject')->nullable();
            $t->string('category')->nullable();
            $t->string('brand')->nullable();
            $t->string('sc_code')->nullable();
            $t->decimal('price', 12, 2)->nullable();
            $t->decimal('discount', 12, 2)->nullable();
            $t->char('row_hash', 40)->unique();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
