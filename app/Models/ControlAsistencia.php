<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profesore;
use  App\Models\Estudiante;
use App\Models\User;

class ControlAsistencia extends Model
{
    use HasFactory;

    protected $primaryKey = 'idControlAsistencias';


    protected $fillable = [

        'estado',
        'idUsuario',
        "idEstudiante",
        "asistencia",
        'diaAsistencia',
        "comentario",
        'idUsuarioIngreso',
        "idUsuarioModifica",

    ];


    public function usuario()
    {
        return $this->hasMany(User::class, 'idUsuario', 'idUsuario');
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'idEstudiante', 'idEstudiante');
    }

    public function userIngreso()
    {
        return $this->belongsTo(User::class, 'idUsuarioIngreso', 'idUsuario');
    }

    public function userModifica()
    {
        return $this->belongsTo(User::class, 'idUsuarioModifica', 'idUsuario');
    }

}
