<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Grado;

use function Laravel\Prompts\alert;

class RegistroUsuariosController extends Controller
{
    use PasswordValidationRules;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grados = Grado::all();
        return view('admin.registro-usuarios', compact('grados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)

    {

        try {
            $input = $request->all();

            // dd($input);
            Validator::make($input, [
                'nombreUsuario' => ['required', 'string', 'max:255'],
                'nombreProfesor' => ['required', 'string', 'max:255'],
                'apellidosProfesor' => ['required', 'string', 'max:255'],
                'password' => $this->passwordRules(),
                'idGrado' => ['required', 'integer', 'max:255'],
                'genero' => ['required', 'string', 'max:255'],
                'telefono' => ['required', 'string', 'max:8'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'direccion' => ['required', 'string', 'max:255'],



            ])->validate();

            User::create([
                'estado' => 1,
                'nombreUsuario' => $input['nombreUsuario'],
                "slug" => Str::slug($input['nombreUsuario']),
                'password' => Hash::make($input['password']),
                'email' => $input['email'],
                'nombreProfesor' => $input['nombreProfesor'],
                'apellidosProfesor' => $input['apellidosProfesor'],
                'direccion' => $input['direccion'],
                'telefono' => $input['telefono'],
                'genero' => $input['genero'],
                'idGrado' => $input['idGrado'],
                "idPerfil" => 1,

            ]);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            echo "<script>alert('Hubo un problema al registrar el usuario: $errorMessage');</script>";
        }
    }

    public function verificarCorreo($email)
    {
        $exists = User::where('email', $email)->exists();
        return response()->json(['exists' => $exists]);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
