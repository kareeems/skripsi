<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_transactions_table.php
public function up()
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->decimal('subtotal', 10, 2);
        $table->decimal('total', 10, 2);
        $table->enum('status', ['paid', 'unpaid', 'partial'])->default('unpaid');
        $table->integer('instalment')->default(10);
        $table->timestamps();

        $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
