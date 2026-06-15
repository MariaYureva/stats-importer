<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $t) {
            $t->id();
            $t->bigInteger('income_id')->index();
            $t->string('number')->nullable();
            $t->date('date')->index();
            $t->dateTime('last_change_date')->nullable();
            $t->string('supplier_article')->nullable();
            $t->string('tech_size')->nullable();
            $t->string('barcode')->nullable();
            $t->integer('quantity')->default(0);
            $t->decimal('total_price', 12, 2)->nullable();
            $t->date('date_close')->nullable();
            $t->string('warehouse_name')->nullable();
            $t->bigInteger('nm_id')->nullable()->index();
            $t->char('row_hash', 40)->unique();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
