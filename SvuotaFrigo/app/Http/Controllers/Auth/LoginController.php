<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Redirect dinamico in base al ruolo
            $redirect = $user->role === 'admin' ? route('admin.dashboard') : route('fridge');
            
            return response()->json([
                'success' => true,
                'redirect' => $redirect
            ]);
        }

        // Se le credenziali sono errate, invia un messaggio di errore
        return response()->json([
            'success' => false,
            'message' => 'Credenziali errate. Riprova.'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Sei stato disconnesso con successo.');
    }
}
