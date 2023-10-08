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
use Illuminate\Support\Facades\Auth;



class EstudiantesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grados = Grado::all();

        return view('estudiantes.registrar-estudiantes', compact('grados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
                'nombreEstudiante' => ['required', 'string', 'max:255'],
                'apellidosEstudiante' => ['required', 'string', 'max:255'],
                'idGrado' => ['required', 'integer'],
                'genero' => ['required', 'string', 'max:255'],
                'nombreEncargado' => ['required', 'string', 'max:255'],
                'apellidosEncargado' => ['required', 'string', 'max:255'],
                'telefono1' => ['required', 'string', 'max:8'],



            ])->validate();




            Estudiante::create([
                'estado' => 1,
                'nombreEstudiante' => $input['nombreEstudiante'],
                'apellidosEstudiante' => $input['apellidosEstudiante'],
                'genero' => $input['genero'],
                'nombreEncargado' => $input['nombreEncargado'],
                'apellidosEncargado' => $input['apellidosEncargado'],
                'telefono1' => $input['telefono1'],
                'telefono2' => $input['telefono2'],
                'idUsuario' => $user->idUsuario,
                'idGrado' => $input['idGrado'],
                'idUsuarioIngreso' => $user->idUsuario,


            ]);

            return redirect()->route('show-listar-estudiantes');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            echo "<script>alert('Hubo un problema al registrar el usuario: $errorMessage');</script>";
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = Auth::user();
        $grados = Grado::all();
        $estudiantes = Estudiante::where('idGrado', $user->idGrado)->get();
        return view('estudiantes.listar-estudiantes', compact('grados', 'estudiantes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = Auth::user();
        $grados = Grado::all();
        $estudiantes = Estudiante::where('idGrado', $user->idGrado)->get();

        return view('estudiantes.editar-estudiantes', compact('grados', 'estudiantes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {

            // Depurar los datos recibidos
            // dd($request->all());

            $input = $request->all();
            $user = Auth::user();

            Validator::make($input, [
                'nombreEstudiante' => ['required', 'string', 'max:255'],
                'apellidosEstudiante' => ['required', 'string', 'max:255'],
                'nombreEncargado' => ['required', 'string', 'max:255'],
                'apellidosEncargado' => ['required', 'string', 'max:255'],
                'telefono1' => ['required', 'string', 'max:8'],
            ])->validate();

            // Obtener el ID del estudiante seleccionado desde el formulario
            $idEstudiante = $request->input('estudianteId');

            // Buscar el estudiante por su ID
            $estudiante = Estudiante::find($idEstudiante);

            // Verificar si el estudiante existe
            if ($estudiante) {
                // Actualizar los campos del estudiante con los valores proporcionados
                $estudiante->nombreEstudiante = $input['nombreEstudiante'];
                $estudiante->apellidosEstudiante = $input['apellidosEstudiante'];
                $estudiante->nombreEncargado = $input['nombreEncargado'];
                $estudiante->apellidosEncargado = $input['apellidosEncargado'];
                $estudiante->telefono1 = $input['telefono1'];
                $estudiante->telefono2 = $input['telefono2'];
                $estudiante->idUsuarioModifica =$user->idUsuario ;

                // Guarda los cambios en la base de datos
                $estudiante->save();

                return redirect()->route('show-listar-estudiantes');
            } else {
                // Si el estudiante no se encuentra, puedes manejar este caso según tus necesidades
                return redirect()->route('show-listar-estudiantes')->with('error', 'Estudiante no encontrado');
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->route('show-listar-estudiantes')->with('error', "Hubo un problema al actualizar el estudiante: $errorMessage");
        }
    }




    public function obtenerDatosEstudiante($id)
    {
        // Lógica para obtener los datos del estudiante con el ID proporcionado
        $estudiante = Estudiante::find($id);

        // Retorna los datos del estudiante en formato JSON
        return response()->json($estudiante);
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
        $estudiantes = Estudiante::where('idGrado', $user->idGrado)->get();


        return view('estudiantes.eliminar-estudiantes', compact('grados', 'estudiantes'));
    }


    public function destroy(Request $request)
    {
        try {

            // Depurar los datos recibidos
            // dd($request->all());

            $input = $request->all();
            $user = Auth::user();


            // Obtener el ID del estudiante seleccionado desde el formulario
            $idEstudiante = $request->input('estudianteId');

            // Buscar el estudiante por su ID
            $estudiante = Estudiante::find($idEstudiante);

            // Verificar si el estudiante existe
            if ($estudiante) {



                // Guarda los cambios en la base de datos
                $estudiante->delete();

                return redirect()->route('show-listar-estudiantes');
            } else {
                // Si el estudiante no se encuentra, puedes manejar este caso según tus necesidades
                return redirect()->route('show-listar-estudiantes')->with('error', 'Estudiante no encontrado');
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->route('show-listar-estudiantes')->with('error', "Hubo un problema al eliminar el estudiante: $errorMessage");
        }
    }
}
