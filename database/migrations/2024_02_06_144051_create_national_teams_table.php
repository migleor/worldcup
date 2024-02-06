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
