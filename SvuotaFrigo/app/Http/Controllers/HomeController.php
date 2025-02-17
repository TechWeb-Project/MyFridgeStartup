<?php

namespace App\Http\Controllers;

use App\Models\Frigo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

     /*
    public function index()
    {
        // Ottieni l'ID dell'utente autenticato
        $userId = auth()->user()->id;

        // Recupera tutti i "frigo" associati all'utente
        $frigoItems = Frigo::where('id_user', $userId)->get();

        // Estrai i prodotti associati ai frigo dell'utente
        $prodotti = $frigoItems->map(function ($frigo) {
            return $frigo->prodotto; // Assicurati che la relazione sia corretta
        });

        // Passa i prodotti alla vista
        return view('home', compact('prodotti'));
    }

    */

    public function index()
{
    // Controlla il ruolo dell'utente
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('user.dashboard');
    }
}
}
