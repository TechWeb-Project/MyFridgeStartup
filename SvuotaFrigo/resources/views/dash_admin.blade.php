<!-- resources/views/dash_admin.blade.php -->
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Amministratore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            height: 100%;
            background: #343a40;
            color: white;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #495057;
            border-radius: 5px;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background: #007bff;
            color: white;
            text-align: center;
        }

        .table td {
            text-align: center;
        }

        .btn-logout {
            background: #dc3545;
            color: white;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background: #b02a37;
        }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center mb-4">Admin Panel</h4>
        <a href="#"><i class="fas fa-user-shield"></i> Profilo</a>
        <a href="#"><i class="fas fa-users"></i> Gestione Utenti</a>
        <a href="#"><i class="fas fa-chart-bar"></i> Statistiche</a>
        <a href="#"><i class="fas fa-cogs"></i> Impostazioni</a>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" class="btn btn-danger mt-4">Logout</button>
</form>

    </div>

    <!-- Contenuto Principale -->
    <div class="content">
        <h1 class="text-center mb-4">Dashboard Amministratore</h1>

        <div class="row">
            <!-- Info Admin -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-user"></i> Informazioni Amministratore
                    </div>
                    <div class="card-body">
                        <p><strong>Nome:</strong> {{ auth()->user()->name }}</p>
                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                        <p><strong>Ruolo:</strong> {{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
            </div>

            <!-- Modifica Password -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <i class="fas fa-key"></i> Modifica Password
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.updatePassword') }}" method="POST">
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

        <!-- Lista degli Utenti -->
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-users"></i> Lista degli Utenti
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Ruolo</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Elimina</button>
                                            <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Modifica</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
