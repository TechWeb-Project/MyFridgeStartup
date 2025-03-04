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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(to right, #1e3c72, #2a5298);
            font-family: 'Poppins', sans-serif;
            color: white;
            min-height: 100vh;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.8);
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
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

        .btn-custom {
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
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
        <div class="container mt-4">
            <div class="row">
                <!-- Profilo Utente -->
                <div class="col-md-6 mx-auto">
                    <div class="card text-center">
                        <div class="card-header bg-primary text-white">
                            Profilo Utente
                        </div>
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

            <!-- Modifica Password -->
            <div class="row mt-4">
                <div class="col-md-6 mx-auto">
                    <div class="card">
                        <div class="card-header bg-warning text-white text-center">
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
                                <button type="submit" class="btn update-btn">Aggiorna Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    Modifica Profilo
                </div>
                <div class="card-body">
                    <form id="updateProfileForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="profile_image" class="form-label">Immagine Profilo</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-success">Aggiorna Profilo</button>
                    </form>
                    <p id="profile-update-msg" class="text-success mt-2" style="display: none;"></p>
                </div>
            </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container d-flex justify-content-between">
            <a class="navbar-brand" href="#">Dashboard Utente</a>
            <div>
                <a href="{{ route('fridge') }}" class="btn btn-custom btn-fridge me-2"><i class="bi bi-fridge"></i> Vai al Frigo</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-custom btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mx-auto text-center">
                <div class="card p-4">
                    <div class="card-header bg-primary text-white">Profilo Utente</div>
                    <div class="card-body">
                        <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default_profile.png') }}" class="profile-img mb-3" alt="Immagine Profilo">
                        <h4 class="user-name">{{ auth()->user()->name }}</h4>
                        <p><i class="bi bi-envelope"></i> {{ auth()->user()->email }}</p>
                        <p><i class="bi bi-person-badge"></i> {{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modifica Profilo -->
        <div class="row mt-4">
            <div class="col-md-6 mx-auto">
                <div class="card p-4">
                    <div class="card-header bg-warning text-dark text-center">Modifica Profilo</div>
                    <div class="card-body">
                        <form id="updateProfileForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="profile_image" class="form-label">Immagine Profilo</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-custom w-100">Aggiorna Profilo</button>
                        </form>
                        <p id="profile-update-msg" class="text-success mt-2" style="display: none;"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modifica Password -->
        <div class="row mt-4">
            <div class="col-md-6 mx-auto">
                <div class="card p-4">
                    <div class="card-header bg-danger text-white text-center">Modifica Password</div>
                    <div class="card-body">
                        <form id="updatePasswordForm">
                            @csrf
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Attuale</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nuova Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Conferma Nuova Password</label>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Aggiorna Password</button>
                        </form>
                        <p id="password-update-msg" class="text-success mt-2" style="display: none;"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script>
        document.getElementById("updatePasswordForm").addEventListener("submit", function(event) {
            event.preventDefault();
            let formData = new FormData(this);

            fetch("{{ route('user.updatePassword') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("password-update-msg").textContent = data.message;
                    document.getElementById("password-update-msg").style.display = "block";
                }
            })
            .catch(error => console.error("Errore:", error));
        });

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
