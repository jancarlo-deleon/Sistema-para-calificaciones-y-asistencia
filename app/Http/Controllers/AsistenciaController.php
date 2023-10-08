<?php

namespace App\Http\Controllers;

use App\Models\ControlAsistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Estudiante;


class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $idGradoUsuario = Auth::user()->idGrado; // Obtiene el idGrado del usuario autenticado
        $estudiantes = Estudiante::where('idGrado', $idGradoUsuario)->get(); // Filtra estudiantes por idGrado

        return view('asistencia.tomar-asistencia', compact('estudiantes'));
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
            $user = Auth::user();
            $input = $request->all();
            $fechaActual = \Carbon\Carbon::now()->format('Y-m-d');

            // Itera sobre los datos enviados desde el formulario
            foreach ($input['asistencia'] as $idEstudiante => $asiste) {
                $comentario = $input['comentario'][$idEstudiante];

                // Busca registros existentes con el mismo idEstudiante y diaAsistencia igual a la fecha actual
                $existingRecord = ControlAsistencia::where('idEstudiante', $idEstudiante)
                    ->where('diaAsistencia', $fechaActual)
                    ->first();

                if ($existingRecord) {
                    // Si existe un registro con la misma fecha actual, actualiza sus valores
                    $existingRecord->update([
                        'asistencia' => $asiste,
                        'comentario' => $comentario,
                    ]);
                } else {
                    // Si no existe un registro con la misma fecha actual, crea uno nuevo
                    ControlAsistencia::create([
                        'estado' => 1,
                        'idUsuario' => $user->idUsuario,
                        'idEstudiante' => $idEstudiante,
                        'asistencia' => $asiste,
                        'diaAsistencia' => $fechaActual,
                        'comentario' => $comentario,
                        'idUsuarioIngreso' => $user->idUsuario,
                    ]);
                }
            }

            return redirect()->route('show-listar-asistencia');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            echo "<script>alert('Hubo un problema al guardar la asistencia: $errorMessage');</script>";
        }
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {

        $idGradoUsuario = Auth::user()->idGrado; // Obtiene el idGrado del usuario autenticado
        $estudiantes = Estudiante::where('idGrado', $idGradoUsuario)->get(); // Filtra estudiantes por idGrado

        return view('asistencia.listar-asistencia', compact('estudiantes'));
    }

    public function buscarAsistencia(Request $request)
    {
        $diaAsistencia = $request->input('diaAsistencia');

        // Buscar los registros de ControlAsistencia para el día específico
        $asistencias = ControlAsistencia::where('diaAsistencia', $diaAsistencia)->get();

        return response()->json(['asistencias' => $asistencias]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {

        $idGradoUsuario = Auth::user()->idGrado; // Obtiene el idGrado del usuario autenticado
        $estudiantes = Estudiante::where('idGrado', $idGradoUsuario)->get(); // Filtra estudiantes por idGrado

        return view('asistencia.editar-asistencia', compact('estudiantes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            $input = $request->all();

            // Itera sobre los datos enviados desde el formulario
            foreach ($input['asistencia'] as $idEstudiante => $asiste) {
                $comentario = $input['comentario'][$idEstudiante];
                $fechaActual = $input['diaAsistencia']; // Usar el valor del input diaAsistencia

                // Busca los registros en la tabla ControlAsistencia por idEstudiante y diaAsistencia
                $existingRecords = ControlAsistencia::where('idEstudiante', $idEstudiante)
                    ->where('diaAsistencia', $fechaActual)
                    ->get();

                foreach ($existingRecords as $existingRecord) {
                    // Actualiza los valores de los registros encontrados
                    $existingRecord->idUsuario = $user->idUsuario;
                    $existingRecord->asistencia = $asiste;
                    $existingRecord->comentario = $comentario;
                    $existingRecord->save();
                }
            }

            return redirect()->route('show-listar-asistencia')->with('success', 'La asistencia se ha actualizado correctamente.');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            return redirect()->route('show-listar-asistencia')->with('error', "Hubo un problema al guardar la asistencia: $errorMessage");
        }
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
