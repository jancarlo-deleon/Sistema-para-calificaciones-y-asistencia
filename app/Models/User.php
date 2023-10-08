<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Perfile;
use App\Models\Actividade;
use App\Models\Profesore;
use App\Models\Estudiante;
use App\Models\AsignacionEstudiante;
use App\Models\ControlAsistencia;
use App\Models\CalificarActividade;
use App\Models\Grado;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $primaryKey = 'idUsuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'estado',
        'nombreUsuario',
        "slug",
        'password',
        'email',
        'nombreProfesor',
        'apellidosProfesor',
        'direccion',
        'telefono',
        'genero',
        'idGrado',
        "idPerfil",

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function adminlte_profile_url(){
        return 'perfil/nombreUsuario';
    }

    public function perfile()
    {
        return $this->hasOne(Perfile::class, 'idPerfil', 'idPerfil');
    }

    public function actividadeIngreso()
    {
        return $this->hasOne(Actividade::class, 'idUsuarioIngreso', 'idUsuario');
    }

    public function actividadeModifica()
    {
        return $this->hasOne(Actividade::class, 'idUsuarioModifica', 'idUsuario');
    }


    public function estudianteIngreso()
    {
        return $this->hasOne(Estudiante::class, 'idUsuarioIngreso', 'idUsuario');
    }

    public function estudianteModifica()
    {
        return $this->hasOne(Estudiante::class, 'idUsuarioModifica', 'idUsuario');
    }

    public function asignacionEstudianteIngreso()
    {
        return $this->hasOne(AsignacionEstudiante::class, 'idUsuarioIngreso', 'idUsuario');
    }

    public function asignacionEstudianteModifica()
    {
        return $this->hasOne(AsignacionEstudiante::class, 'idUsuarioModifica', 'idUsuario');
    }

    public function controlAsistenciaIngreso()
    {
        return $this->hasOne(ControlAsistencia::class, 'idUsuarioIngreso', 'idUsuario');
    }

    public function controlAsistenciaModifica()
    {
        return $this->hasOne(ControlAsistencia::class, 'idUsuarioModifica', 'idUsuario');
    }

    public function calificarActividadeIngreso()
    {
        return $this->hasOne(CalificarActividade::class, 'idUsuarioIngreso', 'idUsuario');
    }

    public function calificarActividadeModifica()
    {
        return $this->hasOne(CalificarActividade::class, 'idUsuarioModifica', 'idUsuario');
    }

    public function grado()
    {
        return $this->hasOne(Grado::class, 'idGrado', 'idGrado');
    }

    public function asignacionEstudiante()
    {
        return $this->belongsTo(AsignacionEstudiante::class, 'idUsuario', 'idUsuario');
    }

    public function controlAsistencia()
    {
        return $this->belongsTo(ControlAsistencia::class, 'idUsuario', 'idUsuario');
    }

    public function califiacarActividade()
    {
        return $this->belongsTo(CalificarActividade::class, 'idUsuario', 'idUsuario');
    }
}
