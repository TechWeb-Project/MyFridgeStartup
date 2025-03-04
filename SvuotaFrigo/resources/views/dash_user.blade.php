<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="{{ asset('css/dashboard/user.css') }}" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #1e3c72, #2a5298);
            font-family: 'Poppins', sans-serif;
            color: white;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #14213d;
            padding: 20px;
            position: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar h4 {
            color: white;
            margin-bottom: 30px;
        }

        .sidebar a, .sidebar form button {
            width: 100%;
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            text-decoration: none;
        }

        .btn-fridge {
            background-color: #fca311;
            color: white;
            border: none;
        }

        .btn-fridge:hover {
            background-color: #ff9f1c;
        }

        .btn-logout {
            background-color: #e63946;
            color: white;
            border: none;
        }

        .btn-logout:hover {
            background-color: #d62839;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }

        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            background: white;
            color: #333;
        }

        .card-header {
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }

        .profile-img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #007bff;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center">User Panel</h4>
        <a href="{{ route('user.dashboard') }}" class="btn btn-primary"><i class="bi bi-person"></i> Profilo</a>
        <a href="{{ route('user.statistics') }}" class="btn btn-primary"><i class="bi bi-graph-up"></i> Statistiche</a>
        <a href="{{ route('fridge') }}" class="btn btn-fridge"><i class="bi bi-fridge"></i> Vai al Frigo</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
    </div>

    <!-- Contenuto Principale -->
    <div class="content">
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="card text-center">
                        <div class="card-header bg-primary text-white">Profilo Utente</div>
                        <div class="card-body">
                            <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default_profile.png') }}"
                                alt="Immagine Profilo" class="profile-img mb-3">
                            <p><strong>Nome:</strong> <span class="user-name">{{ auth()->user()->name }}</span></p>
                            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                            <p><strong>Ruolo:</strong> {{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


              <!-- Modifica Password -->
<div class="row mt-4 justify-content-center">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-header bg-warning text-white">
                Modifica Password
            </div>
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
                    <button type="submit" class="btn btn-warning w-100">Aggiorna Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

<script>
    document.getElementById("updateProfileForm").addEventListener("submit", function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch("{{ route('user.updateProfile') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("profile-update-msg").textContent = data.message;
                    document.getElementById("profile-update-msg").style.display = "block";

                    // Aggiorna l'immagine del profilo
                    if (data.new_image) {
                        document.querySelector(".profile-img").src = data.new_image;
                    }

                    if (data.new_name) {
                        document.querySelector(".user-name").textContent = data.new_name;
                    }
                }
            })
            .catch(error => console.error("Errore:", error));
    });
</script>

</html>
</html>


