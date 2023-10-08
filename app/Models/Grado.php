<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Actividade;
use App\Models\Profesore;

class Grado extends Model
{
    use HasFactory;

    protected $primaryKey = 'idGrado';

    public function actividade()
    {
        return $this->belongsTo(Actividade::class, 'idGrado', 'idGrado');
    }

    public function profesore()
    {
        return $this->belongsTo(Profesore::class, 'idGrado', 'idGrado');
    }

    public function asignacionEstudiante()
    {
        return $this->belongsTo(AsignacionEstudiante::class, 'idGrado', 'idGrado');
    }
}
