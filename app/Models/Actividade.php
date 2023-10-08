<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Grado;
use App\Models\User;
use App\Models\CalificarActividade;
use App\Models\Curso;

class Actividade extends Model
{
    use HasFactory;


    protected $primaryKey = 'idActividad';

    protected $fillable = [

        'estado',
        'nombreActividad',
        'descripcionActividad',
        "calificacionObtenible",
        'idCurso',
        'idGrado',
        'idUsuarioIngreso',
        "idUsuarioModifica",

    ];

    public function grado()
    {
        return $this->hasMany(Grado::class, 'idGrado', 'idGrado');
    }

    public function userIngreso()
    {
        return $this->belongsTo(User::class, 'idUsuarioIngreso', 'idUsuario');
    }

    public function userModifica()
    {
        return $this->belongsTo(User::class, 'idUsuarioModifica', 'idUsuario');
    }

    public function calificarActividades()
    {
        return $this->hasMany(CalificarActividade::class, 'idActividad', 'idActividad');
    }

    public function curso()
    {
        // Establece la relación con Curso y especifica la columna de la clave foránea
        return $this->belongsTo(Curso::class, 'idCurso');
    }
}
