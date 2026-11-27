<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('product_url', 2048);
            $table->string('product_name');
            $table->decimal('price', 12, 2);
            $table->string('currency', 3);
            $table->unsignedInteger('quantity')->default(1);
            $table->text('comment')->nullable();
            $table->decimal('rub_total', 12, 2);
            $table->string('status', 32)->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
