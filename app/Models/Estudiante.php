<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\AsignacionEstudiante;
use App\Models\ControlAsistencia;
use App\Models\CalificarActividade;
use App\Models\Grado;

class Estudiante extends Model
{
    use HasFactory;

    protected $primaryKey = 'idEstudiante';

    protected $fillable = [

        'estado',
        'nombreEstudiante',
        "apellidosEstudiante",
        'genero',
        'nombreEncargado',
        'apellidosEncargado',
        'telefono1',
        'telefono2',
        'idUsuario',
        'idGrado',
        'idUsuarioIngreso',
        "idUsuarioModifica",

    ];


    public function usuario()
    {
        return $this->hasOne(User::class, 'idUsuario', 'idUsuario');
    }

    public function grado()
    {
        return $this->hasOne(Grado::class, 'idGrado', 'idGrado');
    }

    public function userIngreso()
    {
        return $this->belongsTo(User::class, 'idUsuarioIngreso', 'idUsuario');
    }

    public function userModifica()
    {
        return $this->belongsTo(User::class, 'idUsuarioModifica', 'idUsuario');
    }


    public function controlAsistencia()
    {
        return $this->hasMany(ControlAsistencia::class, 'idEstudiante', 'idEstudiante');
    }

    public function calificarActividades()
{
    return $this->hasMany(CalificarActividade::class, 'idEstudiante', 'idEstudiante');
}
}
