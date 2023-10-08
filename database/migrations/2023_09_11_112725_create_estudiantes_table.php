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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id("idEstudiante");
            $table->boolean("estado");
            $table->string("nombreEstudiante");
            $table->string("apellidosEstudiante");
            $table->string("genero");
            $table->string("nombreEncargado");
            $table->string("apellidosEncargado");
            $table->string("telefono1",8);
            $table->string("telefono2",8)->nullable();
            $table->unsignedBigInteger('idUsuario');
            $table->foreign('idUsuario')->references('idUsuario')->on('users');
            $table->unsignedBigInteger('idGrado');
            $table->foreign('idGrado')->references('idGrado')->on('grados');
            $table->unsignedBigInteger('idUsuarioIngreso')->nullable();
            $table->foreign('idUsuarioIngreso')->references('idUsuario')->on('users');
            $table->unsignedBigInteger('idUsuarioModifica')->nullable();
            $table->foreign('idUsuarioModifica')->references('idUsuario')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
