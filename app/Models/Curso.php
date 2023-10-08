<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Actividade;

class Curso extends Model
{
    use HasFactory;

    protected $primaryKey = 'idCurso';

    public function actividades()
    {
        return $this->hasMany(Actividade::class, 'idCurso', 'idCurso');
    }

}
