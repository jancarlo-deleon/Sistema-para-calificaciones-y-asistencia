<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Grado;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Actions\Fortify\PasswordValidationRules;
use App\Models\CalificarActividade;
use Illuminate\Support\Facades\Auth;
use App\Models\Actividade;
use App\Models\Curso;

class ActividadesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $grados = Grado::all();
        $cursos = Curso::all();
        $actividades = Actividade::where('idGrado', $user->idGrado)->get();
        $estudiantes = Estudiante::where('idGrado', $user->idGrado)->get();
        $calificarActividades = CalificarActividade::where('idUsuario', $user->idUsuario)->get();

        return view('actividades.calificar-actividades', compact('grados', 'cursos', 'actividades', 'estudiantes', 'calificarActividades'));
    }

    public function showactcali()
    {

        $user = Auth::user();
        $grados = Grado::all();
        $cursos = Curso::all();
        $actividades = Actividade::where('idGrado', $user->idGrado)->get();
        $estudiantes = Estudiante::where('idGrado', $user->idGrado)->get();


        return view('actividades.listar-actividades-calificadas', compact('grados','cursos', 'actividades', 'estudiantes'));
    }

    public function obtenerDatosActividadParaTabla(Request $request)
    {
        $actividadId = $request->input('actividadId');

        // Realiza una consulta para obtener los datos de calificarActividades relacionados con la actividad seleccionada
        $datosActividad = CalificarActividade::where('idActividad', $actividadId)
            ->with(['estudiante', 'actividade']) // Esto carga las relaciones con las tablas de estudiantes y actividades
            ->get();

        return response()->json($datosActividad);
    }



    public function almacecali(Request $request)
    {

        try {
            $input = $request->all();
            $user = Auth::user();
            //  dd($input);


            // Obtener los valores directamente desde $request
            $calificacionObtenida = $request->input('calificacionObtenida');
            $comentario = $request->input('comentario');
            $idActividad = $request->input('idActividad');
            $idEstudiantes = $request->input('idEstudiante');

            // dd($idEstudiantes);


            // Eliminar registros existentes con el mismo idActividad
            CalificarActividade::where('idActividad', $idActividad)->delete();

            // Iterar sobre los idEstudiantes y crear registros para cada uno
            foreach ($idEstudiantes as $index => $idEstudiante) {
                $calificacionObtenida = $request->input('calificacionObtenida')[$index];
                $comentario = $request->input('comentario')[$index];

                CalificarActividade::create([
                    'estado' => 1,
                    'idUsuario' => $user->idUsuario,
                    'idEstudiante' => $idEstudiante,
                    'idActividad' => $idActividad,
                    'calificacionObtenida' => $calificacionObtenida,
                    'comentario' => $comentario,
                    'idUsuarioIngreso' => $user->idUsuario,
                ]);
            }

            return redirect()->route('show-listar-actividades-calificadas');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            echo "<script>alert('Hubo un problema al calificar la actividad: $errorMessage');</script>";
        }
    }

    public function editarcali()
    {
        $user = Auth::user();
        $grados = Grado::all();
        $cursos = Curso::all();
        $actividades = Actividade::where('idGrado', $user->idGrado)->get();
        $estudiantes = Estudiante::where('idGrado', $user->idGrado)->get();
        $calificarActividades = CalificarActividade::where('idUsuario', $user->idUsuario)->get();

        return view('actividades.editar-actividades-calificadas', compact('grados', 'cursos','actividades', 'estudiantes', 'calificarActividades'));
    }


    public function updatecali(Request $request)
    {

        try {
            // Obtener los datos del formulario
            $calificaciones = $request->input('calificacionObtenida');
            $comentarios = $request->input('comentario');
            $actividadId = $request->input('idActividad');
            $estudiantes = $request->input('idEstudiante');

            // Depura los valores de los Request
            // dd([
            //     'calificaciones' => $calificaciones,
            //     'comentarios' => $comentarios,
            //     'actividadId' => $actividadId,
            //     'estudiantes' => $estudiantes,
            // ]);


            foreach ($calificaciones as $index => $calificacionObtenida) {
                $idEstudiante = $estudiantes[$index];

                // Buscar la calificación existente o crear una nueva si no existe
                CalificarActividade::updateOrCreate(
                    ['idEstudiante' => $idEstudiante, 'idActividad' => $actividadId],
                    ['calificacionObtenida' => $calificacionObtenida, 'comentario' => $comentarios[$index]]
                );
            }

            return redirect()->route('show-listar-actividades-calificadas');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            echo "<script>alert('Hubo un problema al calificar la actividad: $errorMessage');</script>";
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cursos = Curso::all();
        return view('actividades.crear-actividad', compact('cursos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $user = Auth::user();
            // dd($input);

            Validator::make($input, [
                'nombreActividad' => ['required', 'string', 'max:255'],
                'calificacionObtenible' => ['required', 'string', 'max:100'],

            ])->validate();




            Actividade::create([
                'estado' => 1,
                'nombreActividad' => $input['nombreActividad'],
                'descripcionActividad' => $input['descripcionActividad'],
                'calificacionObtenible' => $input['calificacionObtenible'],
                'idCurso' => $request->idCurso,
                'idGrado' => $user->idGrado,
                'idUsuarioIngreso' => $user->idUsuario,


            ]);

            return redirect()->route('show-listar-actividades');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            echo "<script>alert('Hubo un problema al registrar la actividad: $errorMessage');</script>";
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = Auth::user();
        $grados = Grado::all();
        $cursos = Curso::all();
        $actividades = Actividade::where('idGrado', $user->idGrado)->get();


        return view('actividades.listar-actividades', compact('grados', 'actividades', 'cursos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = Auth::user();
        $grados = Grado::all();
        $cursos = Curso::all();
        $actividades = Actividade::where('idGrado', $user->idGrado)->get();


        return view('actividades.editar-actividad', compact('grados', 'actividades', "cursos"));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {

            // Depurar los datos recibidos
            //  dd($request->all());

            $input = $request->all();
            $user = Auth::user();

            Validator::make($input, [
                'nombreActividad' => ['required', 'string', 'max:255'],
                'calificacionObtenible' => ['required', 'string', 'max:100'],
            ])->validate();

            // Obtener el ID de la actividad seleccionada desde el formulario
            $idActividad = $request->input('actividadId');
            // Obtener el ID del curso seleccionado desde el formulario
            $idCurso = $request->input('cursoId');

            // Buscar la actividad por su ID
            $actividad = Actividade::find($idActividad);

            // Verificar si la actividad existe
            if ($actividad) {
                // Actualizar los campos de la actividad con los valores proporcionados
                $actividad->nombreActividad = $input['nombreActividad'];
                $actividad->descripcionActividad = $input['descripcionActividad'];
                $actividad->calificacionObtenible = $input['calificacionObtenible'];
                $actividad->idUsuarioModifica = $user->idUsuario;

                // Guarda los cambios en la base de datos
                $actividad->save();

                return redirect()->route('show-listar-actividades');
            } else {
                // Si la actividad no se encuentra
                return redirect()->route('show-listar-actividades')->with('error', 'Actividad no encontrada');
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->route('show-listar-actividades')->with('error', "Hubo un problema al actualizar la actividad: $errorMessage");
        }
    }


    public function obtenerDatosActividad($id)
    {
        // Lógica para obtener los datos de la actividad con el ID proporcionado
        $actividad = Actividade::with('curso')->find($id);

        // Retorna los datos de la actividad y el nombre del curso en formato JSON
        return response()->json([
            'actividad' => $actividad,
            'nombreCurso' => $actividad->curso->nombreCurso,
        ]);
    }

    public function obtenerActividadesPorCurso($id)
    {
        // Lógica para obtener las actividades del curso con el ID proporcionado
        $actividades = Actividade::where('idCurso', $id)->get();

        // Retorna las actividades en formato JSON
        return response()->json([
            'actividades' => $actividades,
        ]);
    }


    public function obtenerNombreGrado($id)
    {
        // Realiza una consulta para obtener el nombre del grado basado en el ID de grado
        $grado = Grado::find($id);

        // Verifica si se encontró el grado
        if ($grado) {
            // Devuelve el nombre del grado en formato JSON
            return response()->json(['nombreGrado' => $grado->nombreGrado]);
        } else {
            // En caso de que el grado no se encuentre, devuelve un mensaje de error
            return response()->json(['error' => 'Grado no encontrado'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete()
    {

        $user = Auth::user();
        $grados = Grado::all();
        $actividades = Actividade::where('idGrado', $user->idGrado)->get();




        return view('actividades.eliminar-actividad', compact('grados', 'actividades'));
    }

    public function destroy(Request $request)
    {
        try {

            // Depurar los datos recibidos
            // dd($request->all());

            $input = $request->all();
            $user = Auth::user();


            // Obtener el ID de la actividad seleccionada desde el formulario
            $idActividad = $request->input('actividadId');

            // Buscar la actividad por su ID
            $actividad = Actividade::find($idActividad);

            // Verificar si el estudiante existe
            if ($actividad) {



                // Guarda los cambios en la base de datos
                $actividad->delete();

                return redirect()->route('show-listar-actividades');
            } else {
                // Si la actividad no se encuentra
                return redirect()->route('show-listar-actividades')->with('error', 'Actividad no encontrada');
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->route('show-listar-actividades')->with('error', "Hubo un problema al eliminar la actividad: $errorMessage");
        }
    }
}
