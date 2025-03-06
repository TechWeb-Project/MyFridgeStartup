<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo Utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background: url("{{ asset('images/background.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .nav {
            position: relative;
            width: 100%;
            height: 70px;
            background: linear-gradient(90deg, #007bff, #00c6ff);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5em 1.5em;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .logo {
            height: 60px;
        }

        .nav-right a, .nav-right button {
    padding: 10px 20px;
    color: white;
    font-size: 16px;
    font-weight: bold;
    background: transparent;
    border: none;
    border-radius: 5px;
    position: relative;
    transition: all 0.3s ease-in-out;
    text-decoration: none;
    overflow: hidden;
}

.nav-right a::before, .nav-right button::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2); /* Effetto sfondo container */
    border-radius: 10px;
    opacity: 0;
    transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    transform: scale(0.8);
}

.nav-right a:hover::before, .nav-right button:hover::before {
    opacity: 1;
    transform: scale(1);
}

.nav-right a:hover, .nav-right button:hover {
    color: white; /* Mantieni il colore del testo */
}


        .btnfridge {
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            color: white;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 25px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btnfridge:hover {
            background: white;
            color: #007bff;
        }

        .profile-img {
    width: 150px; /* Riduci la dimensione */
    height: 150px;
    border-radius: 50%; /* Mantieni la forma circolare */
    object-fit: cover;
    border: 5px solid #007bff;
}

    </style>
</head>
<body>
    <div class="nav">
        <div class="nav-left">
            <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="logo">
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
