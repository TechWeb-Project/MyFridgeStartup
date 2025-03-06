<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Error;
use App\Models\Recipe;
use App\Models\AIMetric;
use Carbon\Carbon;

class AdminStatisticsController extends Controller
{
    public function index()
    {
        // Dati per errori
        $todayErrors = Error::whereDate('created_at', Carbon::today())->count();
        $commonErrors = Error::select('message')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('message')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
        
        // Dati per trend ricette
        $todayRecipes = Recipe::whereDate('created_at', Carbon::today())->count();
        $avgDailyRecipes = Recipe::whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->count() / 30;
        $totalGenerated = Recipe::count();
        $totalAccepted = Recipe::where('status', 'accepted')->count();
        $acceptanceRate = $totalGenerated > 0 ? round(($totalAccepted / $totalGenerated) * 100, 2) : 0;

        // Dati per performance IA
        $avgGenerationTime = AIMetric::avg('generation_time') ?? 0;
        $successRate = $this->calculateSuccessRate();
        $avgCpuUsage = AIMetric::avg('cpu_usage') ?? 0;
        $avgMemoryUsage = AIMetric::avg('memory_usage') ?? 0;

        // Dati per i grafici
        $errorLabels = $this->getLast7Days();
        $errorData = $this->getErrorDataFor7Days();
        $recipeLabels = $this->getLast7Days();
        $recipeData = $this->getRecipeDataFor7Days();
        $aiPerformanceLabels = $this->getLast7Days();
        $aiPerformanceData = $this->getAIPerformanceDataFor7Days();

        return view('admin_statistics', compact(
            'todayErrors',
            'commonErrors',
            'todayRecipes',
            'avgDailyRecipes',
            'acceptanceRate',
            'avgGenerationTime',
            'successRate',
            'avgCpuUsage',
            'errorLabels',
            'errorData',
            'recipeLabels',
            'recipeData',
            'aiPerformanceLabels',
            'aiPerformanceData'
        ));
    }

    public function getStatisticsData()
    {
        $data = [
            'errorLabels' => $this->getLast7Days(),
            'errorData' => $this->getErrorDataFor7Days(),
            'recipeLabels' => $this->getLast7Days(),
            'recipeData' => $this->getRecipeDataFor7Days(),
            'aiPerformanceLabels' => $this->getLast7Days(),
            'aiPerformanceData' => $this->getAIPerformanceDataFor7Days(),
            'todayErrors' => Error::whereDate('created_at', Carbon::today())->count(),
            'avgDailyRecipes' => Recipe::whereDate('created_at', '>=', Carbon::now()->subDays(30))->count() / 30,
            'acceptanceRate' => $this->calculateAcceptanceRate(),
            'avgGenerationTime' => AIMetric::avg('generation_time') ?? 0,
            'successRate' => $this->calculateSuccessRate(),
            'avgCpuUsage' => AIMetric::avg('cpu_usage') ?? 0,
            'avgMemoryUsage' => AIMetric::avg('memory_usage') ?? 0
        ];

        return response()->json($data);
    }

    private function calculateAcceptanceRate()
    {
        $totalGenerated = Recipe::count();
        $totalAccepted = Recipe::where('status', 'accepted')->count();
        return $totalGenerated > 0 ? round(($totalAccepted / $totalGenerated) * 100, 2) : 0;
    }

    private function calculateSuccessRate()
    {
        $metrics = AIMetric::latest()->limit(100)->get();
        return $metrics->isNotEmpty() 
            ? round($metrics->where('success_rate', 100)->count() / $metrics->count() * 100, 2)
            : 0;
    }

    private function getLast7Days()
    {
        return collect(range(6, 0))->map(function($days) {
            return Carbon::now()->subDays($days)->format('d/m');
        })->toArray();
    }

    private function getErrorDataFor7Days()
    {
        return collect(range(6, 0))->map(function($days) {
            return Error::whereDate('created_at', Carbon::now()->subDays($days))->count();
        })->toArray();
    }

    private function getRecipeDataFor7Days()
    {
        return collect(range(6, 0))->map(function($days) {
            return Recipe::whereDate('created_at', Carbon::now()->subDays($days))->count();
        })->toArray();
    }

    private function getAIPerformanceDataFor7Days()
    {
        return collect(range(6, 0))->map(function($days) {
            $metrics = AIMetric::whereDate('created_at', Carbon::now()->subDays($days))->get();
            
            if ($metrics->isEmpty()) {
                // Return object with zero values instead of just 0
                return [
                    'generation_time' => 0,
                    'success_rate' => 0,
                    'cpu_usage' => 0,
                    'memory_usage' => 0
                ];
            }

            $avgGenerationTime = $metrics->avg('generation_time');
            $successRate = $metrics->where('success_rate', 100)->count() / $metrics->count() * 100;
            $avgCpuUsage = $metrics->avg('cpu_usage');
            $avgMemoryUsage = $metrics->avg('memory_usage');
            
            return [
                'generation_time' => round($avgGenerationTime, 2),
                'success_rate' => round($successRate, 2),
                'cpu_usage' => round($avgCpuUsage, 2),
                'memory_usage' => round($avgMemoryUsage, 2)
            ];
        })->toArray();
    }
}