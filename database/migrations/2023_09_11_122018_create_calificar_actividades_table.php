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
        Schema::create('calificar_actividades', function (Blueprint $table) {
            $table->id("idCalificarActividad");
            $table->boolean("estado");
            $table->unsignedBigInteger('idUsuario');
            $table->foreign('idUsuario')->references('idUsuario')->on('users');
            $table->unsignedBigInteger('idEstudiante');
            $table->foreign('idEstudiante')->references('idEstudiante')->on('estudiantes');
            $table->unsignedBigInteger('idActividad');
            $table->foreign('idActividad')->references('idActividad')->on('actividades');
            $table->decimal('calificacionObtenida',5,2);
            $table->text("comentario")->nullable();
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
        Schema::dropIfExists('calificar_actividades');
    }
};
