<?php

use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\EstudiantesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistroUsuariosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomeController::class)->name('home');




Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    //USUARIOS

    //Registro de Usuarios
    // Ruta de registro (GET)
    Route::get('/registro-usuarios', [RegistroUsuariosController::class, 'index'])
        ->name('index-registro-usuarios');

    // Ruta de registro (POST)
    Route::post('/registro-usuarios', [RegistroUsuariosController::class, 'store'])
        ->name('store-registro-usuarios');
    //Verificacion de correo
    Route::get('/verificar-correo/{email}', [RegistroUsuariosController::class, 'verificarCorreo'])
        ->name('verificar-correo');



    //ESTUDIANTES

    //Registro de Estudiantes
    // Ruta de registro (GET)
    Route::get('/registro-estudiantes', [EstudiantesController::class, 'index'])
        ->name('index-registro-estudiantes');

    // Ruta de registro (POST)
    Route::post('/registro-estudiantes', [EstudiantesController::class, 'store'])
        ->name('store-registro-estudiantes');

    //Listar Estudiantes
    Route::get('/listar-estudiantes', [EstudiantesController::class, 'show'])
        ->name('show-listar-estudiantes');

    //Editar Estudiantes
    Route::get('/editar-estudiantes', [EstudiantesController::class, 'edit'])
        ->name('edit-editar-estudiantes');

    Route::put('/editar-estudiantes', [EstudiantesController::class, 'update'])
        ->name('update-editar-estudiantes');

    Route::get('/obtener-datos-estudiante/{id}', [EstudiantesController::class, 'obtenerDatosEstudiante'])
        ->name('update-obtenerDatosEstudiante');

    Route::get('/obtener-nombre-grado/{id}', [EstudiantesController::class, 'obtenerNombreGrado'])
        ->name('obtener-nombre-grado');


    //Eliminar Estudiantes
    Route::get('/eliminar-estudiantes', [EstudiantesController::class, 'delete'])
        ->name('delete-eliminar-estudiantes');
    Route::delete('/eliminar-estudiante', [EstudiantesController::class, 'destroy'])
        ->name('destroy-eliminar-estudiantes');




    //ASISTENCIA

    //Toma de Asistencia
    // Ruta de registro (GET)
    Route::get('/toma-asistencia', [AsistenciaController::class, 'index'])
        ->name('index-toma-asistencia');

    // Ruta de registro (POST)
    Route::post('/toma-asistencia', [AsistenciaController::class, 'store'])
        ->name('store-toma-asistencia');

    //Editar Asistencia
    Route::get('/editar-asistencia', [AsistenciaController::class, 'edit'])
        ->name('edit-editar-asistencia');
    Route::put('/editar-asistencia', [AsistenciaController::class, 'update'])
        ->name('update-editar-asistencia');

    Route::post('/buscar-asistencia', [AsistenciaController::class, 'buscarAsistencia'])->name('buscar.asistencia');


    //Listar Asistencia
    Route::get('/listar-asistencia', [AsistenciaController::class, 'show'])
        ->name('show-listar-asistencia');


    //ACTIVIDADES

    //CalificarActividades
    // Ruta de registro (GET)
    Route::get('/calificar-actividad', [ActividadesController::class, 'index'])
        ->name('index-calificar-actividad');

    // Ruta de registro (POST)
    Route::post('/calificar-actividad', [ActividadesController::class, 'almacecali'])
        ->name('almacecali-calificar-actividad');

    Route::get('/obtener-datos-actividad', [ActividadesController::class, 'obtenerDatosActividadParaTabla'])->name('obtener-datos-de-actividad');


    //Editar Actividades Calificadas
    Route::get('/editar-calificaciones', [ActividadesController::class, 'editarcali'])
        ->name('editarcali-calificar-actividad');
    Route::put('/editar-calificaciones', [ActividadesController::class, 'updatecali'])
        ->name('updatecali-editar-actividades');

    //Crear activiades
    Route::get('/crear-actividad', [ActividadesController::class, 'create'])
        ->name('create-crear-actividad');

    Route::post('/crear-actividad', [ActividadesController::class, 'store'])
        ->name('almacenar-crear-actividad');

    //Listar Actividades
    Route::get('/listar-actividades', [ActividadesController::class, 'show'])
        ->name('show-listar-actividades');

    //Listar Actividades Calificades
    Route::get('/listar-actividades-calificadas', [ActividadesController::class, 'showactcali'])
        ->name('show-listar-actividades-calificadas');
    Route::get('/obtener-actividades-por-curso/{idCurso}', [ActividadesController::class, 'obtenerActividadesPorCurso'])
        ->name('obtener-actividades-por-curso');

    //Editar Actividades
    Route::get('/editar-actividades', [ActividadesController::class, 'edit'])
        ->name('edit-editar-actividades');

    Route::put('/editar-actividades', [ActividadesController::class, 'update'])
        ->name('update-editar-actividades');

    Route::get('/obtener-datos-actividad/{id}', [ActividadesController::class, 'obtenerDatosActividad'])
        ->name('update-obtenerDatosActividad');

    Route::get('/obtener-nombre-grado/{id}', [ActividadesController::class, 'obtenerNombreGrado'])
        ->name('obtener-nombre-grado');

    Route::get('/obtener-actividades-por-curso/{id}', [ActividadesController::class, 'obtenerActividadesPorCurso'])
        ->name('obtener-actividades-por-curso');

    //Eliminar Actividades
    Route::get('/eliminar-actividades', [ActividadesController::class, 'delete'])
        ->name('delete-eliminar-actividades');
    Route::delete('/eliminar-actividades', [ActividadesController::class, 'destroy'])
        ->name('destroy-eliminar-actividades');
});
