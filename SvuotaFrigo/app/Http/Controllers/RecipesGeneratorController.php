<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Recipe;
use App\Models\Error;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RecipesGeneratorController extends Controller
{
    private function checkAuth()
    {
        $auth = Auth::check();
        if (!$auth) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return null;
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

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

    public function saveRecipe(Request $request)
    {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }
        
        $userId = Auth::id();
        
        $recipe = DB::table('saved_recipes')->insert([
            'user_id' => $userId,
            'recipe_name' => $request->recipe['name'],
            'ingredients' => json_encode($request->ingredients),
            'instructions' => $request->recipe['instructions'],
            'cooking_time' => $request->time,
            'num_people' => $request->num_people,
        ]);
    
        return response()->json(['success' => true]);
    }

    public function saveError(Request $request)
    {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }
        
        $userId = Auth::id();
        
        DB::table('error_logs')->insert([
            'user_id' => $userId,
            'error_type' => $request->type,
            'error_message' => $request->message
        ]);
    
        return response()->json(['success' => true]);
    }

    public function getRecipes(Request $request)
    {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }
        
        $userId = Auth::id();
        return DB::table('saved_recipes')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function index(Request $request)
    {
        $ingredients = $request->query('ingredients', ''); 
        return view('fridge.recipes_generator', compact('ingredients'));
    }

    public function checkUserAuth(Request $request)
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'user' => Auth::check() ? Auth::user() : null
        ]);
    }
}