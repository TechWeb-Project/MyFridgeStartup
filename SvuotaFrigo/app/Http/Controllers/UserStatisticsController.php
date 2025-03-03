<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserStatisticsController extends Controller
{
    public function index()
    {
        return view('user_statistics');
    }
    
    public function getStatisticsData()
    {
        if (!Auth::user()->is_premium) {
            return response()->json([
                'recipes' => [
                    'labels' => [],
                    'values' => []
                ],
                'ingredients' => [
                    'labels' => [],
                    'values' => []
                ]
            ]);
        }

        try {
            // Log user info
            Log::info('User requesting statistics:', [
                'user_id' => Auth::id(),
                'is_premium' => Auth::user()->is_premium
            ]);

            // Recupera le ricette più utilizzate
            $recipes = DB::table('saved_recipes')
                ->select('recipe_name', DB::raw('COUNT(*) as count'))
                ->where('user_id', Auth::id())
                ->groupBy('recipe_name')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get();

            // Log query SQL
            Log::info('SQL Query:', [
                'query' => DB::table('saved_recipes')
                    ->select('recipe_name', DB::raw('COUNT(*) as count'))
                    ->where('user_id', Auth::id())
                    ->groupBy('recipe_name')
                    ->orderBy('count', 'desc')
                    ->limit(5)
                    ->toSql(),
                'bindings' => [Auth::id()]
            ]);

            // Check if recipes exist
            if ($recipes->isEmpty()) {
                Log::warning('No recipes found for user:', ['user_id' => Auth::id()]);
            }

            // Recupera gli ingredienti più utilizzati
            $ingredients = DB::table('saved_recipes')
                ->select('ingredients')
                ->where('user_id', Auth::id())
                ->get()
                ->map(function ($recipe) {
                    try {
                        $decoded = json_decode($recipe->ingredients, true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            Log::error('JSON decode error:', [
                                'error' => json_last_error_msg(),
                                'ingredients' => $recipe->ingredients
                            ]);
                            return [];
                        }
                        Log::info('Successfully decoded ingredients:', [
                            'decoded' => $decoded
                        ]);
                        return $decoded;
                    } catch (\Exception $e) {
                        Log::error('Error processing ingredients:', [
                            'error' => $e->getMessage(),
                            'ingredients' => $recipe->ingredients
                        ]);
                        return [];
                    }
                })
                ->filter()
                ->flatten(1)
                ->groupBy('name')
                ->map(function ($group) {
                    return $group->count();
                })
                ->sortDesc()
                ->take(5);

            $response = [
                'recipes' => [
                    'labels' => $recipes->pluck('recipe_name')->values()->toArray(),
                    'values' => $recipes->pluck('count')->values()->toArray()
                ],
                'ingredients' => [
                    'labels' => $ingredients->keys()->values()->toArray(),
                    'values' => $ingredients->values()->toArray()
                ]
            ];

            Log::info('Final response:', $response);

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Statistics error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Errore nel recupero delle statistiche: ' . $e->getMessage(),
                'recipes' => [
                    'labels' => [],
                    'values' => []
                ],
                'ingredients' => [
                    'labels' => [],
                    'values' => []
                ]
            ], 500);
        }
    }
}