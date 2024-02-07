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
        Schema::create('national_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lang',3)->unique();
            $table->enum('federation',['AFRICA','ASIA','CONCACAF','CONMEBOL','UEFA','ANFITRION'])->nullable();
            $table->string('flag_image_path')->nullable();
            $table->enum('status',['Grupos','Octavos','Cuartos','Semis','Tercero','final'])->default('Grupos');
            $table->string('pos_grupos')->nullable();
            $table->string('llave_octavos')->nullable();
            $table->string('llave_cuartos')->nullable();
            $table->string('llave_semi')->nullable();
            $table->string('llave_tercero')->nullable();
            $table->string('llave_final')->nullable();
            $table->integer('position')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('national_teams');
    }
};
