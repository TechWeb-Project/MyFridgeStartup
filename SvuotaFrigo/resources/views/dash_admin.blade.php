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
    <link href="{{ asset('css/dashboard/admin.css') }}" rel="stylesheet">
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center mb-4">Admin Panel</h4>
        <a href="#"><i class="fas fa-user-shield"></i> Profilo</a>
        <a href="{{ route('admin.statistics') }}"><i class="fas fa-chart-bar"></i> Statistiche</a>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger mt-4 w-100">Logout</button>
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
                    <div class="card-header bg-warning text-white text-center">
                        <i class="fas fa-key"></i> Modifica Password
                    </div>
                    <div class="card-body text-center">
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
                                    <th>Premium</th>
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
                                    <td>
                                        @if($user->is_premium)
                                            <span class="badge bg-success">Premium</span>
                                        @else
                                            <span class="badge bg-secondary">Base</span>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning editUserBtn"
                                            data-id="{{ $user->id }}"
                                            data-role="{{ $user->role }}"
                                            data-bs-toggle="modal" data-bs-target="#editUserModal">
                                            <i class="fas fa-edit"></i> Modifica
                                        </button>
                                        <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE') <!-- Indica a Laravel di usare il metodo DELETE -->
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questo utente?');">
                                                <i class="fas fa-trash"></i> Elimina
                                            </button>
                                        </form>

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

    <!-- Modale Modifica Utente -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifica Ruolo Utente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" method="POST">
                        @csrf
                        <input type="hidden" id="userId" name="user_id">
                        <div class="mb-3">
                            <label for="userRole" class="form-label">Seleziona il nuovo ruolo</label>
                            <select class="form-select" id="userRole" name="role">
                                <option value="user">Utente Normale</option>
                                <option value="admin">Amministratore</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let editUserModal = document.getElementById("editUserModal");
            let editUserForm = document.getElementById("editUserForm");

            editUserModal.addEventListener("show.bs.modal", function(event) {
                let button = event.relatedTarget;
                let userId = button.getAttribute("data-id");
                let userRole = button.getAttribute("data-role");

                // Imposta il valore dell'input hidden con l'ID dell'utente
                document.getElementById("userId").value = userId;
                document.getElementById("userRole").value = userRole;

                // Imposta dinamicamente l'action del form
                editUserForm.setAttribute("action", `/admin/update-role/${userId}`);
            });
        });
    </script>


</body>

</html>