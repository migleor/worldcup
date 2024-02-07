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
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->string('grupo')->nullable();
            $table->string('local')->nullable();
            $table->integer('local_team_id')->nullable();
            $table->string('visitante')->nullable();
            $table->integer('visitante_team_id')->nullable();
            $table->string('fase');
            $table->timestamp('fecha');
            $table->string('emparejamiento')->nullable();
            $table->integer('goles_local')->default(0);
            $table->integer('goles_visitante')->default(0);
            $table->integer('amarillas_local')->default(0);
            $table->integer('amarillas_visitante')->default(0);
            $table->integer('rojas_local')->default(0);
            $table->integer('rojas_visitante')->default(0);
            $table->integer('puntos_local')->default(0);
            $table->integer('puntos_visitante')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
