<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Recipe;
use App\Models\Error;
use App\Models\AIMetric;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RecipesGeneratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        Log::debug('Recipe count check', [
            'user_id' => $user->id,
            'count' => $recipeCount,
            'remaining' => 10 - $recipeCount
        ]);

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
            'isPremium' => Auth::user()->is_premium,
            'user_id' => Auth::id()
        ]);
    }

    public function generateRecipe(Request $request) {
        DB::enableQueryLog();
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

        // Misurazione tempo iniziale
        $startTime = microtime(true);
        
        // Misura utilizzo CPU iniziale
        $cpuInitial = sys_getloadavg()[0];
        $memoryInitial = memory_get_usage(true);

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
                        try {
                            DB::beginTransaction();
                            
                            // Log dei dati pre-inserimento
                            Log::debug('Pre-insert data', [
                                'user_id' => Auth::id(),
                                'timestamp' => now(),
                                'auth_check' => Auth::check()
                            ]);
                            
                            // Modifica l'inserimento per ottenere l'ID inserito
                            $id = DB::table('recipe_generations')->insertGetId([
                                'user_id' => Auth::id(),
                                'created_at' => now()
                            ]);
                            
                            // Log del risultato dell'inserimento
                            Log::debug('Insert result', [
                                'inserted_id' => $id,
                                'user_id' => Auth::id()
                            ]);
                            
                            if (!$id) {
                                throw new \Exception('Failed to insert recipe generation record - no ID returned');
                            }
                            
                            // Verifica che il record sia stato effettivamente inserito
                            $recordExists = DB::table('recipe_generations')
                                ->where('id', $id)
                                ->exists();
                                
                            Log::debug('Record verification', [
                                'exists' => $recordExists,
                                'id' => $id
                            ]);
                            
                            if (!$recordExists) {
                                throw new \Exception('Record not found after insert');
                            }
                            
                            DB::commit();
                            
                            Log::info('Recipe generation record created successfully', [
                                'id' => $id,
                                'user_id' => Auth::id()
                            ]);

                            // Aggiungi questo dopo l'inserimento per debug
                            $checkQuery = "SELECT * FROM recipe_generations WHERE user_id = ? AND DATE(created_at) = CURDATE()";
                            $results = DB::select($checkQuery, [Auth::id()]);
                            Log::debug('Direct query check', ['results' => $results]);
                            
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error('Failed to insert recipe generation record', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString(),
                                'sql' => DB::getQueryLog(),
                                'user_id' => Auth::id()
                            ]);
                            
                            return response()->json([
                                'error' => 'Failed to track recipe generation',
                                'debug_message' => $e->getMessage()
                            ], 500);
                        }
                    }

                    // Calcolo metriche
                    try {
                        Log::debug('Starting AI metrics calculation');
                        
                        $endTime = microtime(true);
                        $generationTime = round($endTime - $startTime, 2);
                        $cpuFinal = sys_getloadavg()[0];
                        $cpuUsage = round(($cpuFinal - $cpuInitial) * 100, 2);
                        $memoryUsage = round((memory_get_usage(true) - $memoryInitial) / 1024 / 1024, 2);

                        Log::info('AI metrics calculated', [
                            'generation_time' => $generationTime,
                            'success_rate' => 100,
                            'cpu_usage' => $cpuUsage,
                            'memory_usage' => $memoryUsage
                        ]);

                        try {
                            DB::beginTransaction();
                            
                            DB::table('ai_metrics')->insert([
                                'generation_time' => $generationTime,
                                'success_rate' => 100,
                                'cpu_usage' => $cpuUsage,
                                'memory_usage' => $memoryUsage,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error('Failed to save AI metrics', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);
                        }

                        Log::info('AI metrics saved successfully');

                    } catch (\Exception $e) {
                        Log::error('Failed to save AI metrics', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
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

            // In caso di errore
            $endTime = microtime(true);
            $generationTime = round($endTime - $startTime, 2);
            $cpuFinal = sys_getloadavg()[0];
            $cpuUsage = round(($cpuFinal - $cpuInitial) * 100, 2);
            $memoryUsage = round((memory_get_usage(true) - $memoryInitial) / 1024 / 1024, 2);

            try {
                DB::beginTransaction();
                
                DB::table('ai_metrics')->insert([
                    'generation_time' => $generationTime,
                    'success_rate' => 0,
                    'cpu_usage' => $cpuUsage,
                    'memory_usage' => $memoryUsage,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to save AI metrics', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            return response()->json([
                'error' => 'Il servizio di generazione ricette non è al momento disponibile. Per favore riprova tra qualche minuto.'
            ], 503);
        } catch (\Exception $e) {
            Log::error('Exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            // In caso di errore
            $endTime = microtime(true);
            $generationTime = round($endTime - $startTime, 2);
            $cpuFinal = sys_getloadavg()[0];
            $cpuUsage = round(($cpuFinal - $cpuInitial) * 100, 2);
            $memoryUsage = round((memory_get_usage(true) - $memoryInitial) / 1024 / 1024, 2);

            try {
                DB::beginTransaction();
                
                DB::table('ai_metrics')->insert([
                    'generation_time' => $generationTime,
                    'success_rate' => 0,
                    'cpu_usage' => $cpuUsage,
                    'memory_usage' => $memoryUsage,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to save AI metrics', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

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
                'updated_at' => now(),
                'status' => 'accepted'
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

    private function extractQuantityFromName($ingredient)
    {
        $name = $ingredient['name'];
        $quantity = $ingredient['quantity'];
        $unit = $ingredient['unit'];

        // Check for weight in parentheses
        if (preg_match('/\((?:circa\s+)?(\d+)\s*g\)/', $name, $matches)) {
            // If we find a weight in grams in parentheses, use that instead
            return [
                'name' => trim(preg_replace('/\s*\([^)]+\)/', '', $name)), // Remove the parentheses part
                'quantity' => (float) $matches[1],
                'unit' => 'g'
            ];
        }

        // Return original values if no weight in parentheses is found
        return [
            'name' => $name,
            'quantity' => $quantity,
            'unit' => $unit
        ];
    }

    public function updateFridgeQuantities(Request $request)
    {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }
    
        try {
            $userId = Auth::id();
            $ingredients = $request->ingredients;
            $updatedProducts = [];
    
            DB::beginTransaction();
    
            foreach ($ingredients as $ingredient) {
                // Extract proper quantity and unit from ingredient name if present
                $processedIngredient = $this->extractQuantityFromName($ingredient);
                
                // Normalizza il nome dell'ingrediente per la ricerca
                $ingredientName = strtolower(trim($processedIngredient['name']));
                
                // Trova il prodotto nel frigo dell'utente
                $userProduct = DB::table('prodotto')
                    ->join('frigo', 'prodotto.id_prodotto', '=', 'frigo.id_prodotto')
                    ->where('frigo.id_user', $userId)
                    ->where(DB::raw('LOWER(prodotto.nome_prodotto)'), $ingredientName)
                    ->select('prodotto.*', 'frigo.id as frigo_id')
                    ->first();
    
                if (!$userProduct) {
                    Log::warning("Product not found in user's fridge", [
                        'ingredient' => $ingredientName
                    ]);
                    continue;
                }
    
                // Converti le unità di misura se necessario
                $quantityToSubtract = $this->convertQuantity(
                    $processedIngredient['quantity'],
                    $processedIngredient['unit'],
                    $userProduct->unita_misura
                );
    
                // Aggiorna la quantità del prodotto
                DB::table('prodotto')
                    ->where('id_prodotto', $userProduct->id_prodotto)
                    ->update([
                        'quantita' => DB::raw("GREATEST(0, quantita - $quantityToSubtract)"),
                        'updated_at' => now()
                    ]);
    
                // Se la quantità è 0, elimina il prodotto dal frigo
                $updatedProduct = DB::table('prodotto')
                    ->where('id_prodotto', $userProduct->id_prodotto)
                    ->first();
    
                // Add updated product to the response array
                if ($updatedProduct) {
                    $updatedProducts[] = [
                        'id_prodotto' => $updatedProduct->id_prodotto,
                        'nome_prodotto' => $updatedProduct->nome_prodotto,
                        'quantita' => $updatedProduct->quantita,
                        'unita_misura' => $updatedProduct->unita_misura,
                        'data_scadenza' => $updatedProduct->data_scadenza
                    ];
                }
    
                if ($updatedProduct && $updatedProduct->quantita <= 0) {
                    DB::table('frigo')
                        ->where('id', $userProduct->frigo_id)
                        ->delete();
                    
                    DB::table('prodotto')
                        ->where('id_prodotto', $userProduct->id_prodotto)
                        ->delete();
                }
            }
    
            DB::commit();
            return response()->json([
                'success' => true,
                'updatedProducts' => $updatedProducts
            ]);
    
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
        
        DB::table('errors')->insert([
            'user_id' => $userId,
            'type' => $request->type,
            'message' => $request->message
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