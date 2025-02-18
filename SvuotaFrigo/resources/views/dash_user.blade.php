<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            font-weight: bold;
        }

        .navbar {
            background-color: #007bff;
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #007bff;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }

        .logout-btn:hover {
            background-color: #bb2d3b;
        }
    </style>
</head>
<body>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Dashboard Utente</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Impostazioni</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn logout-btn">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="row">
            <!-- Informazioni Personali -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        Informazioni Personali
                    </div>
                    <div class="card-body text-center">
                        <p><strong>Nome:</strong> {{ auth()->user()->name }}</p>
                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                        <p><strong>Ruolo:</strong> {{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
            </div>

            <!-- Immagine Profilo -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white text-center">
                        Immagine del Profilo
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/profile/' . auth()->user()->profile_image) }}" 
                             alt="Immagine Profilo" class="profile-img mb-3">
                        <form action="{{ route('user.updateProfileImage') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input type="file" name="profile_image" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Carica Immagine</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modifica Password -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-warning text-white text-center">
                        Modifica Password
                    </div>
                    <div class="card-body text-center">
                        <a href="{{ route('user.changePasswordPage') }}" class="btn btn-warning">
                            Cambia Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
