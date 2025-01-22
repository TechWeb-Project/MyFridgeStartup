<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FrigoAIController extends Controller
{
    public function generateReceipe(Request $request) {
        $ingredients = $request->input('ingredients');
        $time        = $request->input('time');
        
        // Chiamata all'API Python
        $response = Http::post('http://127.0.0.1:5000/generate', [
            'ingredients' => $ingredients,
            'time' => $time
        ]);

        if ($response->successful()) {
            return response()->json(['receipe' => $response->json()['receipe']]);
        } else {
            return response()->json(['error' => 'Errore nel generare la ricetta'], 500);
        }
    }
}