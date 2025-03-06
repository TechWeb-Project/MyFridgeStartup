<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo Utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('css/dashboard/user_dashboard.css') }}">

</head>

<body>
    <div class="nav">
        <div class="nav-left">
            <img src="{{ asset('images/waisteless.png') }}" alt="Logo" class="logo">
            <a href="{{ route('fridge') }}" class="btnfridge">
                <i class="bi bi-house-door"></i> Torna al Frigo
            </a>
        </div>
        <div class="nav-right">
            <a href="{{ route('user.dashboard') }}">Profilo</a>
            <a href="{{ route('user.statistics') }}">Statistiche</a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit"> <i class="bi bi-box-arrow-right"></i> Logout </button>
            </form>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-header bg-primary text-white">Profilo Utente</div>
                    <div class="card-body">
                        <img src="{{ auth()->user()->profile_image ? asset('storage/profile_images/' . auth()->user()->profile_image) : asset('images/default_profile.png') }}"
                             alt="Immagine Profilo" class="profile-img mb-3">
                        <p><strong>Nome:</strong> <span class="user-name">{{ auth()->user()->name }}</span></p>
                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 justify-content-center">
            <div class="col-md-5">
                <div class="card text-center">
                    <div class="card-header bg-primary text-white">Modifica Password</div>
                    <div class="card-body">
                        <form action="{{ route('user.updatePassword') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="password" name="current_password" class="form-control" placeholder="Password Attuale" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="new_password" class="form-control" placeholder="Nuova Password" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="new_password_confirmation" class="form-control" placeholder="Conferma Nuova Password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Aggiorna Password</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card text-center">
                    <div class="card-header bg-primary text-white">Cambia Foto Profilo</div>
                    <div class="card-body">
                        <form action="{{ route('user.updateProfileImage') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input type="file" name="profile_image" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Aggiorna Immagine</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
