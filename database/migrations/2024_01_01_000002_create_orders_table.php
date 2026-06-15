<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->string('g_number')->nullable()->index();
            $t->date('date')->index();
            $t->dateTime('last_change_date')->nullable();
            $t->string('supplier_article')->nullable();
            $t->string('tech_size')->nullable();
            $t->string('barcode')->nullable();
            $t->decimal('total_price', 12, 2)->nullable();
            $t->integer('discount_percent')->nullable();
            $t->string('warehouse_name')->nullable();
            $t->string('oblast')->nullable();
            $t->bigInteger('income_id')->nullable();
            $t->bigInteger('odid')->nullable();
            $t->bigInteger('nm_id')->nullable()->index();
            $t->string('subject')->nullable();
            $t->string('category')->nullable();
            $t->string('brand')->nullable();
            $t->boolean('is_cancel')->default(false);
            $t->dateTime('cancel_dt')->nullable();
            $t->char('row_hash', 40)->unique();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
