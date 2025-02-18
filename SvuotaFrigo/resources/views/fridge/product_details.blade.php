@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div id="product-card" class="card shadow-lg p-4 rounded-lg text-center bg-light border-success" style="background-image: url('https://www.wallpaperflare.com/static/871/591/649/eat-italian-cuisine-cooking-book-cover-background-wallpaper.jpg'); background-size: cover; background-position: center;">
        <h2 class="mb-3 fw-bold" style="color: #F8C471;">Dettagli Prodotto 🍏</h2>
        <p class="fs-5"><strong>Nome:</strong> <span id="product-name" class="text-dark">{{ $prodotto->nome_prodotto }}</span></p>
        <p class="fs-5"><strong>Data Scadenza:</strong> <span id="product-expiry" class="text-danger">{{ $prodotto->data_scadenza }}</span></p>
        <p class="fs-5">
            <strong>Categoria:</strong>
            <span class="text-dark">
                <p class="fs-5"><strong>Categoria:</strong> <span class="text-dark">{{ $categoria }}</span></p>
            </span>
        </p>
        <div class="d-flex justify-content-between mt-3">
            <button id="edit-btn" class="btn btn-lg rounded-pill px-4" style="background-color: #239B56; color: white; text-transform: uppercase;">Modifica ✨</button>
            <button id="delete-btn" class="btn btn-lg rounded-pill px-4" style="background-color: #239B56; color: white; text-transform: uppercase;">Elimina 🗑️</button>
        </div>
    </div>
    
    <div id="edit-form" class="card shadow-lg p-4 rounded-lg text-center mt-3 border-primary" style="display: none; background-color: #FCF3CF;">
        <h3 class="text-primary fw-bold">Modifica Prodotto ✍️</h3>
        <input type="text" id="edit-name" class="form-control mb-2 fs-5" value="{{ $prodotto->nome_prodotto }}">
        <input type="date" id="edit-expiry" class="form-control mb-2 fs-5" value="{{ $prodotto->data_scadenza }}">
        <button id="save-btn" class="btn btn-lg rounded-pill px-4" style="background-color: #239B56; color: white; text-transform: uppercase;">Salva 💾</button>
        <button id="cancel-btn" class="btn btn-lg rounded-pill px-4" style="background-color: #239B56; color: white; text-transform: uppercase;">Annulla ❌</button>
    </div>
</div>

<script>
    document.getElementById('edit-btn').addEventListener('click', function() {
        document.getElementById('edit-form').style.display = 'block';
    });

    document.getElementById('cancel-btn').addEventListener('click', function() {
        document.getElementById('edit-form').style.display = 'none';
    });

    document.getElementById('save-btn').addEventListener('click', function() {
        let name = document.getElementById('edit-name').value;
        let expiry = document.getElementById('edit-expiry').value;

        fetch(`/fridge_dashboard`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ nome_prodotto: name, data_scadenza: expiry })
        }).then(response => response.json()).then(data => {
            if (data.success) {
                document.getElementById('product-name').textContent = name;
                document.getElementById('product-expiry').textContent = expiry;
                document.getElementById('edit-form').style.display = 'none';
            } else {
                alert("Errore nell'aggiornamento del prodotto");
            }
        });
    });

    document.getElementById('delete-btn').addEventListener('click', function() {
        if (confirm("Sei sicuro di voler eliminare il prodotto?")) {
            fetch(`/fridge_dashboard`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(response => response.json()).then(data => {
                alert(data.message);
                document.getElementById('product-card').remove();
            });
        }
    });
</script>
@endsection
