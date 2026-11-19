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
    Schema::connection('order_sqlite')->create('orders', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id'); // Tham chiếu logic sang UserService
        $table->decimal('total_amount', 12, 2);
        $table->string('status')->default('pending_payment');
        $table->timestamps();
    });
}
public function down(): void
{
    Schema::connection('order_sqlite')->dropIfExists('orders');
}
};
