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
use Carbon\Carbon;

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

    private function checkRecipeLimit()
    {
        $user = Auth::user();
        if ($user->is_premium) {
            return ['allowed' => true, 'remaining' => -1]; // -1 indicates unlimited
        }

        $today = Carbon::today();
        $recipeCount = DB::table('recipe_generations')
            ->where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->count();

        $remaining = 10 - $recipeCount;
        return [
            'allowed' => $remaining > 0,
            'remaining' => $remaining
        ];
    }

    public function getRemainingRecipes()
    {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }

        $limit = $this->checkRecipeLimit();
        return response()->json([
            'remaining' => $limit['remaining'],
            'isPremium' => Auth::user()->is_premium
        ]);
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function generateRecipe(Request $request) {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }

        $limit = $this->checkRecipeLimit();
        if (!$limit['allowed']) {
            return response()->json([
                'error' => 'limit_reached',
                'message' => 'Hai raggiunto il limite giornaliero di ricette generate'
            ], 403);
        }

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
                    
                    if (!Auth::user()->is_premium) {
                        DB::table('recipe_generations')->insert([
                            'user_id' => Auth::id(),
                            'created_at' => now()
                        ]);
                    }

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
        
        try {
            $userId = Auth::id();
            
            if (!$request->recipe['name'] || !$request->recipe['instructions']) {
                throw new \Exception('Dati della ricetta incompleti');
            }
            
            if (empty($request->ingredientsWithQuantities)) {
                throw new \Exception('Nessun ingrediente specificato');
            }
            
            DB::beginTransaction();
            
            $recipe = DB::table('saved_recipes')->insert([
                'user_id' => $userId,
                'recipe_name' => $request->recipe['name'],
                'ingredients' => json_encode($request->ingredientsWithQuantities, JSON_UNESCAPED_UNICODE),
                'instructions' => $request->recipe['instructions'],
                'time' => $request->time,  
                'num_people' => $request->num_people,
                'created_at' => now(),
                'updated_at' => now()
            ]);
    
            DB::commit();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving recipe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Errore durante il salvataggio della ricetta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateFridgeQuantities(Request $request)
    {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }
    
        try {
            $userId = Auth::id();
            $ingredients = $request->ingredients;
    
            Log::info('Updating fridge quantities', [
                'user_id' => $userId,
                'ingredients' => $ingredients
            ]);
    
            DB::beginTransaction();
    
            foreach ($ingredients as $ingredient) {
                // Normalizza il nome dell'ingrediente per la ricerca
                $ingredientName = strtolower(trim($ingredient['name']));
                
                // Trova l'ingrediente nel frigo dell'utente
                $userIngredient = DB::table('user_ingredients')
                    ->where('user_id', $userId)
                    ->where(DB::raw('LOWER(ingredient_name)'), $ingredientName)
                    ->first();
    
                if (!$userIngredient) {
                    Log::warning("Ingredient not found in user's fridge", [
                        'ingredient' => $ingredientName
                    ]);
                    continue;
                }
    
                // Converti le unità di misura se necessario
                $quantityToSubtract = $this->convertQuantity(
                    $ingredient['quantity'], 
                    $ingredient['unit'], 
                    $userIngredient->unita_misura
                );
    
                // Aggiorna la quantità
                DB::table('user_ingredients')
                    ->where('user_id', $userId)
                    ->where('ingredient_name', $userIngredient->ingredient_name)
                    ->update([
                        'quantity' => DB::raw("GREATEST(0, quantity - $quantityToSubtract)")
                    ]);
    
                // Elimina gli ingredienti con quantità 0
                DB::table('user_ingredients')
                    ->where('user_id', $userId)
                    ->where('quantity', 0)
                    ->delete();
            }
    
            DB::commit();
            return response()->json(['success' => true]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating fridge quantities', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'aggiornamento delle quantità nel frigo'
            ], 500);
        }
    }

    private function convertQuantity($quantity, $fromUnit, $toUnit)
    {
        // Normalizza le unità
        $fromUnit = strtolower($fromUnit);
        $toUnit = strtolower($toUnit);
    
        // Se le unità sono uguali, return la quantità originale
        if ($fromUnit === $toUnit) {
            return $quantity;
        }
    
        // Conversioni comuni
        $conversions = [
            'kg' => ['g' => 1000],
            'g' => ['kg' => 0.001],
            'l' => ['ml' => 1000],
            'ml' => ['l' => 0.001],
            'cucchiai' => ['g' => 15], // approssimativo
            'cucchiaini' => ['g' => 5], // approssimativo
        ];
    
        // Cerca la conversione
        if (isset($conversions[$fromUnit][$toUnit])) {
            return $quantity * $conversions[$fromUnit][$toUnit];
        }
    
        // Se non trova una conversione, return la quantità originale
        return $quantity;
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