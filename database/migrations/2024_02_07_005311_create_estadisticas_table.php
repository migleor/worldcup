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
        Schema::create('estadisticas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('national_team_id');
            $table->string('fase')->nullable();
            $table->integer('goles')->default(0);
            $table->integer('amarillas')->default(0);
            $table->integer('rojas')->default(0);
            $table->integer('puntos')->default(0);
            $table->integer('ganados')->default(0);
            $table->integer('perdidos')->default(0);
            $table->integer('empatados')->default(0);
            $table->string('grupo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadisticas');
    }
};
