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
        Schema::create('users', function (Blueprint $table) {
            $table->id("idUsuario");
            $table->boolean("estado");
            $table->string('nombreUsuario');
            $table->string('slug')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string("nombreProfesor");
            $table->string("apellidosProfesor");
            $table->string("direccion");
            $table->string("telefono",8);
            $table->string("genero");
            $table->unsignedBigInteger('idGrado');
            $table->foreign('idGrado')->references('idGrado')->on('grados');
            $table->unsignedBigInteger('idPerfil');
            $table->foreign('idPerfil')->references('idPerfil')->on('perfiles');
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
