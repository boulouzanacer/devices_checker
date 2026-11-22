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
        Schema::create('device_histories', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->nullable()->index();
            $table->string('device_name')->nullable();
            $table->string('revendeur')->nullable()->index();
            $table->string('event', 30)->index();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_histories');
    }
};
