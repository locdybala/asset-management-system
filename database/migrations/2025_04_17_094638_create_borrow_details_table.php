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
        // database/migrations/xxxx_xx_xx_create_borrow_details_table.php
        Schema::create('borrow_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrow_id');
            $table->unsignedBigInteger('device_item_id');

            $table->foreign('borrow_id')->references('id')->on('borrows')->onDelete('cascade');
            $table->foreign('device_item_id')->references('id')->on('device_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_details');
    }
};
