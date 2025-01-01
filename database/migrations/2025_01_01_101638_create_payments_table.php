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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_id');
            $table->string('payment_method')->nullable();
            $table->string('invoice_number')->unique();
            $table->integer('amount');
            $table->string('status')->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->json('callback_data')->nullable();
            $table->timestamps();

            // Membuat foreign key constraint
            $table->foreign('reference_id')
                    ->references('id')
                    ->on('instalments')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
