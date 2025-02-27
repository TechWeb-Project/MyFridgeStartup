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
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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