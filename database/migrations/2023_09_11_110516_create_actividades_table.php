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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id("idActividad");
            $table->boolean("estado");
            $table->string("nombreActividad");
            $table->text("descripcionActividad");
            $table->decimal('calificacionObtenible',5,2);
            $table->unsignedBigInteger('idCurso');
            $table->foreign('idCurso')->references('idCurso')->on('cursos');
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
        Schema::dropIfExists('actividades');
    }
};
