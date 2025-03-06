<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/login'; // Reindirizza dopo il reset

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email', // Verifica che l'email esista
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);

        return $this->traitReset($request);
    }
}
