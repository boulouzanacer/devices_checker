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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->unique()->index();
            $table->string('name')->nullable();
            $table->string('revendeur')->nullable();
            $table->boolean('is_blocked')->default(false)->index();
            $table->timestamp('first_seen')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->timestamp('last_disconnect')->nullable();
            $table->unsignedInteger('heartbeat_count')->default(0);
            $table->string('app_version')->nullable();
            $table->string('package_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
