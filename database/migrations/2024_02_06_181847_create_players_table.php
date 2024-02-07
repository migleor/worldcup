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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('national_team_id');
            $table->integer('edad');
            $table->integer('dorsal');
            $table->enum('posicion', ['Portero','Defensa','Centrocampista','Delantero']);
            $table->string('profile_image')->nullable();
            $table->timestamps();
            $table->foreign('national_team_id')->references('id')->on('national_teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
