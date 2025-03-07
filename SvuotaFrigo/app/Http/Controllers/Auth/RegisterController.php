<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(Request $request)
    {
        // Validazione dati con messaggi personalizzati in italiano
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'Il campo nome è obbligatorio.',
            'name.max' => 'Il nome non può superare i 255 caratteri.',
            'email.required' => 'Il campo email è obbligatorio.',
            'email.email' => 'Inserisci un indirizzo email valido.',
            'email.max' => 'L\'email non può superare i 255 caratteri.',
            'email.unique' => 'Questa email è già stata registrata.',
            'password.required' => 'Il campo password è obbligatorio.',
            'password.min' => 'La password deve contenere almeno 6 caratteri.',
            'password.confirmed' => 'Le password non coincidono.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Creazione dell'utente
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Login automatico
        Auth::login($user);

        return response()->json([
            'success' => true,
            'redirect' => '/dashboard'
        ]);
    }
}
