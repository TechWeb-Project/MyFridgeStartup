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
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .navbar {
            background-color: #007bff;
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .profile-img {
            width: 140px;
            height: 140px;
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

        /* Stile del form */
        .password-form {
            max-width: 500px;
            margin: auto;
        }

        .password-form .input-group-text {
            background-color: #007bff;
            color: white;
            border-radius: 5px 0 0 5px;
        }

        .password-form .form-control {
            border-radius: 0 5px 5px 0;
        }

        .update-btn {
            background-color: #ffcc00;
            color: #333;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .update-btn:hover {
            background-color: #ffb700;
            transform: scale(1.05);
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

            if(data.new_name){
                document.querySelector(".user-name").textContent = data.new_name;
            }
        }
    })
    .catch(error => console.error("Errore:", error));
});
</script>

</html>
