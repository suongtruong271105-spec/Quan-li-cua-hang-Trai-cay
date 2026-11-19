<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::connection('order_sqlite')->create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
        $table->unsignedBigInteger('fruit_id'); // Tham chiếu logic sang ProductService
        $table->decimal('quantity_kg', 10, 2);
        $table->decimal('unit_price', 10, 2);
        $table->timestamps();
    });
}
public function down(): void
{
    Schema::connection('order_sqlite')->dropIfExists('order_items');
}
};
