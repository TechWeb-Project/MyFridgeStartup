<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    // use RegistersUsers;

    /**
     * Dove reindirizzare gli utenti dopo la registrazione.
     * Lo modifichiamo per mandarli alla pagina di login.
     */
    protected $redirectTo = '/login';

    /**
     * Crea un'istanza del controller.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Restituisce la vista del form di registrazione.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Validazione dei dati di registrazione.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Registra un nuovo utente.
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
    
        $user = $this->create($request->all());
    
        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'Errore durante la registrazione']);
        }
    
        // Non effettua il login, ma rimanda alla homepage con un messaggio
        return redirect('/')->with('registered', 'Registrato con successo! Ora puoi accedere.');
    }
    

    /**
     * Crea un nuovo utente nel database.
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
