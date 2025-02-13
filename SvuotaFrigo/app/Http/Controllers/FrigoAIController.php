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
}