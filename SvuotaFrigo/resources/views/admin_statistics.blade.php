<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Statistiche - Dashboard Amministratore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="{{ asset('css/dashboard/admin.css') }}" rel="stylesheet">
    <script src="{{ asset('js/admin_statistics.js') }}" defer></script>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center mb-4">Admin Panel</h4>
        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-user-shield"></i> Profilo</a>
        <a href="{{ route('admin.statistics') }}" class="active"><i class="fas fa-chart-bar"></i> Statistiche</a>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger mt-4 w-100">Logout</button>
        </form>
    </div>

    <!-- Contenuto Principale -->
    <div class="content">
        <h1 class="text-center mb-4">Statistiche del Sistema</h1>

        <div class="row">
            <!-- Errori Utenti -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-exclamation-triangle"></i> Errori Utenti
                    </div>
                    <div class="card-body">
                        <canvas id="errorChart"></canvas>
                        <div class="mt-3">
                            <p><strong>Totale errori oggi:</strong> {{ $todayErrors }}</p>
                            <p><strong>Errori più frequenti:</strong></p>
                            <ul>
                                @foreach($commonErrors as $error)
                                <li>{{ $error->message }} ({{ $error->count }} volte)</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trend Ricette -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-utensils"></i> Trend Ricette
                    </div>
                    <div class="card-body">
                        <canvas id="recipeChart"></canvas>
                        <div class="mt-3">
                            <p><strong>Ricette generate oggi:</strong> {{ $todayRecipes }}</p>
                            <p><strong>Media giornaliera:</strong> {{ $avgDailyRecipes }}</p>
                            <p><strong>Tasso di accettazione:</strong> {{ $acceptanceRate }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiche IA -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-robot"></i> Statistiche IA
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="aiPerformanceChart"></canvas>
                            </div>
                            <div class="col-md-6">
                                <h5>Metriche Chiave:</h5>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Tempo medio di generazione
                                        <span class="badge bg-primary rounded-pill" id="avgGenerationTime">{{ $avgGenerationTime }}s</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Richieste completate con successo
                                        <span class="badge bg-success rounded-pill" id="successRate">{{ $successRate }}%</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Utilizzo medio CPU
                                        <span class="badge bg-warning rounded-pill" id="avgCpuUsage">{{ $avgCpuUsage }}%</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Utilizzo medio memoria
                                        <span class="badge bg-info rounded-pill" id="avgMemoryUsage">{{ $avgMemoryUsage }} MB</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>