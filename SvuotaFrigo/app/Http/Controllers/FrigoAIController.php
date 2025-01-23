<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FrigoAIController extends Controller
{
    public function generateRecipe(Request $request) {
        $ingredients = $request->input('ingredients');
        $time        = $request->input('time');

        Log::info('Ricevuto richiesta per generare ricetta', ['ingredients' => $ingredients, 'time' => $time]);
        
        // Chiamata all'API Python
        $response = Http::post('http://127.0.0.1:5000/generate', [
            'ingredients' => $ingredients,
            'time' => $time
        ]);

        if ($response->successful()) {
            Log::info('Risposta API Python ricevuta con successo', ['response' => $response->json()]);
            return response()->json(['recipe' => $response->json()['recipe']]);
        } else {
            Log::error('Errore nella risposta API Python', ['response' => $response->json()]);
            return response()->json(['error' => 'Errore nel generare la ricetta'], 500);
        }
    }
}