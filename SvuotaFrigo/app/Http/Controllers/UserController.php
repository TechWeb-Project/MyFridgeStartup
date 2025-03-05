<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    
     
    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($user->profile_image && Storage::exists('storage/profile_images/' . $user->profile_image)) {
            Storage::delete('storage/profile_images/' . $user->profile_image);
        }

        // Salva la nuova immagine
        $fileName = time() . '.' . $request->file('profile_image')->extension();
        $path=$request->file('profile_image')->storeAs('storage/profile_images/', $fileName);

        // Aggiorna il percorso dell'immagine nel database
        $user->profile_image = $fileName;
        $user->save();

        return redirect()->back()->with('success', 'Immagine del profilo aggiornata con successo!');
    }
    


    /**
     * Aggiorna la password dell'utente.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Verifica che la password attuale sia corretta
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'La password attuale non è corretta.']);
        }

        // Aggiorna la password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password aggiornata con successo!');
    }

    public function showChangePasswordPage() {
        return view('user.change_password'); 
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
    
        // Validazione
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Aggiorna il nome
        $user->name = $request->name;
    
        // Se viene caricata una nuova immagine
        if ($request->hasFile('profile_image')) {
            // Elimina la vecchia immagine se esiste
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
    
            // Salva la nuova immagine
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }
    
        $user->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Profilo aggiornato con successo!',
            'new_name' => $user->name,
            'new_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null
        ]);
    }
    

}
