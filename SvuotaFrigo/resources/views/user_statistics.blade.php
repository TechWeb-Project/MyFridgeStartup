<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .nav {
            position: relative;
            width: 100%;
            height: 70px;
            background:rgb(69, 157, 186);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5em 1.5em;
        }

        .logo {
            height: 60px;
        }

        .btn {
            padding: 0.7em 1.5em;
            color:rgb(255, 255, 255);
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            background: transparent;
            border: none;
            transition: 0.1s;
        }

        .btn-premium{
            padding: 0.7em 1.5em;
            color:rgb(0, 0, 0);
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            background: transparent;
            border: none;
            transition: 0.1s;
            text-decoration: none;

        }
        .btn:hover {
            background: #fff3;
        }

        .btnfridge {
            font-size: 19px;
            font-weight: bold;
            text-decoration: none;
            color: black;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-right {
            display: flex;
            gap: 15px;
        }


        button-prm {
            
  background: #fbca1f;
  font-family: inherit;
  padding: 0.6em 1.3em;
  font-weight: 900;
  font-size: 18px;
  border: 3px solid black;
  border-radius: 0.4em;
  box-shadow: 0.1em 0.1em;
  cursor: pointer;
  position: relative;
    top: 25px;
}

button-prm:hover {
  transform: translate(-0.05em, -0.05em);
  box-shadow: 0.15em 0.15em;
}

button-prm:active {
  transform: translate(0.05em, 0.05em);
  box-shadow: 0.05em 0.05em;
}
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="nav">
        <div class="nav-left">
            <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="logo">
            <a href="{{ route('fridge') }}" class="btnfridge">Torna al Frigo</a>
        </div>
        <div class="nav-right">
            <a href="{{ route('user.dashboard') }}" class="btn">Profilo</a>
            <a href="{{ route('user.statistics') }}" class="btn">Statistiche</a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-logout">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Contenuto Principale -->
    <div class="container mt-4">
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
                <div class="premium-card text-center">
                    <i class="bi bi-star-fill text-warning" style="font-size: 48px;"></i>
                    <h2 class="mt-4">Sblocca le Statistiche Premium</h2>
                    <p class="lead">Passa a Premium per accedere alle statistiche dettagliate delle tue ricette!</p>
                    <button-prm>
                    <a href="#" class="btn-premium" data-bs-toggle="modal" data-bs-target="#premiumModal">
                        <i class="bi bi-crown"></i> Acquista Premium
                    </a>
                    </button-prm>
                </div>
            </div>
        </div>
        @endif
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/user_statistics.js') }}"></script>

</body>
</html>
