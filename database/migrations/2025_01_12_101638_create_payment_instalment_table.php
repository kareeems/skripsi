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
        Schema::create('payment_instalment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('instalment_id');
            $table->integer('amount');  // Jumlah yang dibayar untuk instalmen tersebut
            $table->timestamps();

            // Menambahkan foreign key
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('instalment_id')->references('id')->on('instalments')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_instalment');
    }
};
