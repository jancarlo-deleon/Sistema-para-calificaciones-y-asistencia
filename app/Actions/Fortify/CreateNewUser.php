<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'nombreUsuario' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            // 'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'estado' =>1,
            'nombreUsuario' => $input['nombreUsuario'],
            "slug" => Str::slug($input['nombreUsuario']) ,
            'password' => Hash::make($input['password']),
            'email' => $input['email'],
            'nombreProfesor' => "Usuario Creado",
            'apellidosProfesor' => "Por aparte",
            'direccion' => "Ciudad",
            'telefono' => "00000000",
            'genero' => "Masculino",
            'idGrado' => 1,
            "idPerfil" => 1,

        ]);
    }
}
