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
    Schema::connection('product_sqlite')->create('fruits', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->decimal('price_per_kg', 10, 2);
        $table->string('origin')->nullable();
        $table->text('description')->nullable();
        $table->string('image_url')->nullable(); // <-- Thêm dòng này
        $table->timestamps();
    });
}
public function down(): void
{
    Schema::connection('product_sqlite')->dropIfExists('fruits');
}
};
