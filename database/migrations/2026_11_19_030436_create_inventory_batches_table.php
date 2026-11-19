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
    Schema::connection('inventory_sqlite')->create('inventory_batches', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('fruit_id'); // Tham chiếu logic sang ProductService
        $table->decimal('quantity_kg', 10, 2);
        $table->date('import_date');
        $table->date('expiry_date');
        $table->timestamps();
    });
}
public function down(): void
{
    Schema::connection('inventory_sqlite')->dropIfExists('inventory_batches');
}
};
