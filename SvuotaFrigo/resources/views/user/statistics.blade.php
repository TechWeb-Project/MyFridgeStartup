<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiche Utente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="{{ asset('css/dashboard/statistics.css') }}" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center mb-4">User Panel</h4>
        <a href="{{ route('user.dashboard') }}"><i class="bi bi-person"></i> Profilo</a>
        <a href="{{ route('user.statistics') }}"><i class="bi bi-graph-up"></i> Statistiche</a>
        <form action="{{ route('logout') }}" method="POST" class="d-inline px-3">
            @csrf
            <button type="submit" class="btn btn-danger mt-4 w-100">Logout</button>
        </form>
    </div>

    <!-- Contenuto Principale -->
    <div class="content">
        <div class="container">
            @if(auth()->user()->is_premium)
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <i class="bi bi-pie-chart"></i> Ricette più utilizzate
                        </div>
                        <div class="card-body">
                            <canvas id="recipesChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-bar-chart"></i> Ingredienti più usati
                        </div>
                        <div class="card-body">
                            <canvas id="ingredientsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="premium-card">
                        <i class="bi bi-star-fill text-warning" style="font-size: 48px;"></i>
                        <h2 class="mt-4">Sblocca le Statistiche Premium</h2>
                        <p class="lead">Passa a Premium per accedere alle statistiche dettagliate delle tue ricette!</p>
                        <a href="#" class="btn btn-warning btn-lg mt-3" data-bs-toggle="modal" data-bs-target="#premiumModal">
                            <i class="bi bi-crown"></i> Acquista Premium
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal per Coming Soon -->
    <div class="modal fade" id="premiumModal" tabindex="-1" aria-labelledby="premiumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="premiumModalLabel">
                        <i class="bi bi-gem text-warning"></i> Premium
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h3>Coming Soon</h3>
                    <p class="lead">La funzionalità Premium sarà disponibile a breve!</p>
                    <i class="bi bi-clock-history text-primary" style="font-size: 48px;"></i>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    @if(auth()->user()->is_premium)
    <script>
        // Esempio di codice per i grafici (da personalizzare con i dati reali)
        const recipesCtx = document.getElementById('recipesChart').getContext('2d');
        const ingredientsCtx = document.getElementById('ingredientsChart').getContext('2d');

        new Chart(recipesCtx, {
            type: 'pie',
            data: {
                labels: ['Ricetta 1', 'Ricetta 2', 'Ricetta 3'],
                datasets: [{
                    data: [30, 20, 50],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            }
        });

        new Chart(ingredientsCtx, {
            type: 'bar',
            data: {
                labels: ['Ingrediente 1', 'Ingrediente 2', 'Ingrediente 3'],
                datasets: [{
                    label: 'Frequenza di utilizzo',
                    data: [12, 19, 3],
                    backgroundColor: '#4CAF50'
                }]
            }
        });
    </script>
    @endif
</body>

</html>