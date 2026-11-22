<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('revendeurs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index();
            $table->timestamps();
        });

        // Reporte les revendeurs deja presents sur les devices existants.
        $existing = DB::table('devices')
            ->whereNotNull('revendeur')
            ->where('revendeur', '<>', '')
            ->distinct()
            ->pluck('revendeur');

        foreach ($existing as $name) {
            DB::table('revendeurs')->insertOrIgnore([
                'name' => trim($name),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revendeurs');
    }
};
