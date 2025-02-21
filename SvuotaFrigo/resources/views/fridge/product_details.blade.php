<head>
    <link rel="stylesheet" href="{{ asset('css/product_details.css') }}">
</head>

<div class="container mt-4">
    <div class="bg-wrapper">
        <div class="content-container">
            <!-- @if (!isset($prodotto) || is_null($prodotto)) -->
                <div id="product-card" class="card shadow-lg p-4 rounded-lg text-center bg-light border-success">
                    <h2 class="mb-3 fw-bold">Dettagli Prodotto</h2>
                    <div class="alert alert-warning">Nessun prodotto selezionato.</div>
                </div>
            <!-- @else -->
                <div id="product-card" class="card shadow-lg p-4 rounded-lg text-center bg-light border-success">
                    <h2 class="mb-3 fw-bold">Dettagli Prodotto</h2>
                    <p class="fs-5"><strong>Nome:</strong> <span id="product-name" class="text-dark bg-light p-2 border rounded d-inline-block">{{ $prodotto->nome_prodotto }}</span></p>
                    <p class="fs-5"><strong>Categoria:</strong> <span class="text-dark bg-light p-2 border rounded d-inline-block">{{ $prodotto->categoria->categoria->nome_categoria}}</span></p>
                    <p class="fs-5"><strong>Data Scadenza:</strong> <span id="product-expiry" class="text-dark bg-light p-2 border rounded d-inline-block">{{ $prodotto->data_scadenza->format('d/m/Y') }}</span></p>

                    <div class="d-flex justify-content-between mt-3">
                        <button id="edit-btn" class="btn custom-btn">
                            Modifica
                            <img src="{{ asset('images/icona_modifica.png') }}" alt="Modifica">
                        </button>
                        <button id="deleteProductBtn" class="btn btn-danger">
                            Elimina
                            <img src="{{ asset('images/icona_elimina.png') }}" alt="Elimina">
                        </button>
                    </div>
                    
                </div>

                <div id="edit-form" class="card shadow-lg p-4 rounded-lg text-center mt-3 border-primary d-none">
                    <h3 class="text-primary fw-bold">Modifica Prodotto</h3>
                    <input type="hidden" id="edit-id" value="{{ $prodotto->id_prodotto }}">
                    <input type="text" id="edit-name" class="form-control mb-2 fs-5" value="{{ $prodotto->nome_prodotto }}">
                    <input type="date" id="edit-expiry" class="form-control mb-2 fs-5" value="{{ $prodotto->data_scadenza->format('Y-m-d') }}">

                    <div class="d-flex justify-content-center gap-3">
                        <button id="save-btn" class="btn custom-btn">Salva</button>
                        <button id="cancel-btn" class="btn btn-secondary">Annulla</button>
                    </div>
                </div>

                <div id="deleteConfirmation" class="card shadow-lg p-3 rounded-lg text-center mt-3 border-danger d-none">
                    <p class="mb-3 text-danger fw-bold">Sei sicuro di voler eliminare questo prodotto?</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button id="cancelDeleteBtn" class="btn btn-secondary">Annulla</button>
                        <button id="confirmDeleteBtn" class="btn btn-danger">Conferma Eliminazione</button>
                    </div>
                </div>

                <div id="deleteMessage" class="alert alert-success text-center mt-3 d-none">
                    Prodotto eliminato con successo.
                </div>
            <!-- @endif -->
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButton = document.getElementById('edit-btn');
        const editForm = document.getElementById('edit-form');
        const cancelEditBtn = document.getElementById('cancel-btn');
        const saveButton = document.getElementById('save-btn');
    
        const deleteButton = document.getElementById('deleteProductBtn'); 
        const deleteDiv = document.getElementById('deleteConfirmation'); 
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn'); 
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn'); 
        const deleteMessage = document.getElementById('deleteMessage'); 
        const productCard = document.getElementById('product-card');
    
        // Funzione per chiudere entrambi i div
        function hideAllForms() {
            editForm.classList.add('d-none');
            deleteDiv.classList.add('d-none');
        }
    
        // Cliccando su "Modifica", chiude eliminazione e mostra modifica
        editButton.addEventListener('click', function() {
            hideAllForms();
            editForm.classList.toggle('d-none');
        });
    
        // Cliccando su "Annulla" nel form di modifica, lo chiude
        cancelEditBtn.addEventListener('click', function() {
            editForm.classList.add('d-none');
        });
    
        // Cliccando su "Elimina", chiude modifica e mostra eliminazione
        deleteButton.addEventListener('click', function() {
            hideAllForms();
            deleteDiv.classList.toggle('d-none');
        });
    
        // Cliccando su "Annulla" nel div di eliminazione, lo chiude
        cancelDeleteBtn.addEventListener('click', function() {
            deleteDiv.classList.add('d-none');
        });

                // Quando clicco su "Salva", aggiorna solo nome e data di scadenza
                
                saveButton.addEventListener('click', function() {
                const idProdotto = document.getElementById('edit-id').value;
                const newName = document.getElementById('edit-name').value;
                const newExpiry = document.getElementById('edit-expiry').value;

                fetch(`/fridge_dashboard`, {
                    method: 'PUT',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_prodotto: idProdotto,
                        nome_prodotto: newName,
                        data_scadenza: newExpiry
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Aggiorna la vista
                        document.getElementById('product-name').innerText = newName;
                        document.getElementById('product-expiry').innerText = newExpiry;

                        // Nasconde il form di modifica
                        editForm.classList.add('d-none');
                    } else {
                        alert("Errore nell'aggiornamento del prodotto.");
                    }
                })
                .catch(error => console.error('Errore:', error));
            });


        // Conferma eliminazione e aggiorna il DB
        confirmDeleteBtn.addEventListener('click', function() {
            fetch(`/fridge_dashboard`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideAllForms(); // Nasconde tutto
                    productCard.classList.add('d-none'); // Nasconde il prodotto
                    deleteMessage.classList.remove('d-none'); // Mostra messaggio di eliminazione
                } else {
                    alert("Errore nell'eliminazione del prodotto.");
                }
            })
            .catch(error => console.error('Errore:', error));
        });
    });

    
    </script>

    <script>
    // Stampa il messaggio di debug sulla console

    // Puoi anche stampare altre informazioni, ad esempio:
    console.log("Dettagli prodotto:", @json($prodotti));
</script>
    

