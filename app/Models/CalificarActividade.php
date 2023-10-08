<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profesore;
use App\Models\Estudiante;
use App\Models\Actividade;
use App\Models\User;

class CalificarActividade extends Model
{
    use HasFactory;

    protected $primaryKey = 'idCalificarActividad';

    protected $fillable = [

        'estado',
        'idUsuario',
        "idEstudiante",
        "idActividad",
        'calificacionObtenida',
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

public function actividade()
{
    return $this->belongsTo(Actividade::class, 'idActividad', 'idActividad');
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
