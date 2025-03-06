<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Mostra la dashboard
    public function index()
    {
        // Recupera tutti gli utenti dal database
        $users = User::all(); // Recupera tutti gli utenti 


        // Passa i dati degli utenti alla vista
        return view('dash_admin', compact('users')); // Usa il metodo compact per passare la variabile
    }

    // Funzione per cambiare la password
    public function updatePassword(Request $request)
    {
        $user = Auth::user(); // Ottieni l'utente autenticato

        // Validazione dei dati
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Verifica se la password attuale è corretta
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La password attuale non è corretta.']);
        }

        // Aggiorna la password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Redirect con messaggio di successo
        return back()->with('success', 'La tua password è stata aggiornata con successo!');
    }


    public function updateUserRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $newRole = $request->role;
    
        if ($newRole === 'premium') {
            $user->is_premium = 1;
            $user->role = 'user'; // Premium rimane un utente normale con vantaggi extra
        } else {
            $user->is_premium = 0;
            $user->role = $newRole;
        }
    
        $user->save();
        
        return redirect()->back()->with('success', 'Ruolo utente aggiornato con successo!');
    }
    
    

    // Elimina utente
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Utente eliminato con successo.');
    }
}



