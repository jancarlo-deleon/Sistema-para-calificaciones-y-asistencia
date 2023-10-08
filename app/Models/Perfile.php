<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Perfile extends Model
{
    use HasFactory;

    protected $primaryKey = 'idPerfil';

    public function user()
    {
        return $this->belongsTo(User::class, 'idPerfil', 'idPerfil');
    }
}
