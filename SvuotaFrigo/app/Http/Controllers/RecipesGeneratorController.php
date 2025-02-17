<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Recipe;
use App\Models\Error;

class RecipesGeneratorController extends Controller
{
    public function generateRecipe(Request $request) {
        $ingredients = $request->input('ingredients');
        $time        = $request->input('time');

        try {
            $response = Http::post('http://127.0.0.1:5000/generate-recipe', [
                'ingredients' => $ingredients,
                'time' => $time
            ]);
            
            Log::info('Python API raw response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);
    
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['recipe'])) {
                    return response()->json(['recipe' => $data['recipe']]);
                } else {
                    Log::error('Missing recipe in response', ['data' => $data]);
                    return response()->json(['error' => 'Invalid response format'], 500);
                }
            } else {
                Log::error('Python API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json(['error' => 'API error'], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function saveRecipe(Request $request) {
        $ingredients = $request->input('ingredients');
        $time = $request->input('time');
        $recipe = $request->input('recipe');

        try {
            Recipe::create([
                'ingredients' => $ingredients,
                'time' => $time,
                'generate_receipe' => $recipe
            ]);
            return response()->json(['success' => 'Ricetta salvata con successo!']);
        } catch (\Exception $e) {
            Log::error('Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function saveError(Request $request) {
        $type = $request->input('type');
        $message = $request->input('message');

        try {
            Error::create([
                'type' => $type,
                'message' => $message
            ]);
            return response()->json(['success' => 'Errore salvato con successo!']);
        } catch (\Exception $e) {
            Log::error('Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}