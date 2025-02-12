<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Titolo -->
        <h1 class="text-center mb-4">Dashboard Utente</h1>

        <div class="row">
            <!-- Sezione informazioni personali -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Informazioni Personali
                    </div>
                    <div class="card-body">
                        <p><strong>Nome:</strong> {{ auth()->user()->name }}</p>
                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                        <p><strong>Ruolo:</strong> {{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
            </div>

            <!-- Sezione immagine profilo -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        Immagine del Profilo
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/profile/' . auth()->user()->profile_image) }}" alt="Immagine Profilo" class="img-thumbnail mb-3" style="width: 150px; height: 150px; object-fit: cover;">
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

        <!-- Sezione modifica password -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        Modifica Password
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.updatePassword') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Attuale</label>
                                <input type="password" name="current_password" id="current_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nuova Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Conferma Nuova Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Aggiorna Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
