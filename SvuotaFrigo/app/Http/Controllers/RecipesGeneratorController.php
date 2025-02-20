<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Recipe;
use App\Models\Error;
use Illuminate\Http\Client\ConnectionException;

class RecipesGeneratorController extends Controller
{
    public function generateRecipe(Request $request) {
        $ingredients = $request->input('ingredients');
        $time = $request->input('time');
        $num_people = $request->input('num_people', 1); 
        $rejected = $request->input('rejected', false);

        Log::info('Request received for generating recipe', [
            'ingredients' => $ingredients,
            'time' => $time,
            'num_people' => $num_people,
            'rejected' => $rejected
        ]);

        try {
            $response = Http::timeout(10)
                ->post(env('RECIPE_SERVICE_URL').'/generate-recipe', [
                    'ingredients' => $ingredients,
                    'time' => $time,
                    'num_people' => $num_people, 
                    'rejected' => $rejected
                ]);
            
            Log::info('Python API raw response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);
    
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['recipe'])) {
                    Log::info('Recipe generated successfully', ['recipe' => $data['recipe']]);
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
                return response()->json(['error' => $response->json()['error'] ?? 'API error'], $response->status());
            }
        } catch (ConnectionException $e) {
            Log::error('Connection error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Il servizio di generazione ricette non è al momento disponibile. Per favore riprova tra qualche minuto.'
            ], 503);
        } catch (\Exception $e) {
            Log::error('Exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function saveRecipe(Request $request) {
        $ingredients = $request->input('ingredients');
        $time = $request->input('time');
        $recipe = $request->input('recipe');
        $num_people = $request->input('num_people'); 

        try {
            Recipe::create([
                'ingredients' => $ingredients,
                'time' => $time,
                'recipe' => $recipe,
                'num_people' => $num_people 
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