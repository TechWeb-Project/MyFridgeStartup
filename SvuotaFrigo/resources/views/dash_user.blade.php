<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo Utente</title>
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
            background: rgb(69, 157, 186);
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
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            background: transparent;
            border: none;
            transition: 0.1s;
        }

        .btn:hover {
            background: #fff3;
        }

        .btn-box{
            padding: 0.7em 1.5em;
            color: #000;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            background: transparent;
            border: none;
            transition: 0.1s;
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

        .profile-img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #007bff;
        }

        .custom-card {
            border: 2px solid rgb(69, 157, 186);
        }

        .custom-header {
            color: black !important;
            background-color: rgb(69,157,186) !important;
            border-bottom: 2px solid rgb(69, 157, 186) !important;
        }

        .btn-box {
    background-color: rgb(69, 157, 186); /* 🔥 Colore di sfondo */
    color: white; /* 🔥 Testo bianco per contrasto */
    font-size: 16px;
    font-weight: bold;
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
    display: block; /* 🔥 Assicura che sia visibile */
    width: 100%; /* 🔥 Rende il pulsante grande */
    text-align: center;
}

.btn-box:hover {
    background-color: rgb(50, 120, 160); /* 🔥 Effetto hover più scuro */
    box-shadow: 0 0 5px rgb(69, 157, 186);
}

/* Per il bottone 'Aggiorna Immagine' */
.btn-update-img {
    background-color: rgb(69, 157, 186);
    color: white;
    font-size: 16px;
    font-weight: bold;
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
    width: 100%;
}

.btn-update-img:hover {
    background-color: rgb(50, 120, 160);
    box-shadow: 0 0 5px rgb(69, 157, 186);
}



        
    </style>
</head>
<body>
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
            <!-- Modifica Password -->
            <div class="col-md-5">
                <div class="card text-center custom-card">
                    <div class="card-header custom-header">Modifica Password</div>
                    <div class="card-body">
                        <form action="{{ route('user.updatePassword') }}" method="POST" class="password-form">
                            @csrf
                            <div class="mb-3 input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Password Attuale" required>
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Nuova Password" required>
                            </div>
                            <div class="mb-3 input-group">
                                <span class="input-group-text"><i class="bi bi-check-circle-fill"></i></span>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Conferma Nuova Password" required>
                            </div>
                            <button type="submit" class="btn-box">Aggiorna Password</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Cambia Foto Profilo -->
            <div class="col-md-5">
                <div class="card text-center custom-card">
                    <div class="card-header custom-header">Cambia Foto Profilo</div>
                    <div class="card-body">
                        <form action="{{ route('user.updateProfileImage') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input type="file" name="profile_image" class="form-control" required>
                            </div>
                            <button type="submit" class="btn-update-img">Aggiorna Immagine</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>