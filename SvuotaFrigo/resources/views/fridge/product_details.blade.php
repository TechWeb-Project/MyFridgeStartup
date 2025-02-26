<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <input type="hidden" id="product-id-hidden">
</head>

<div class="container mt-4">
    <div class="bg-wrapper">
        <div class="content-container">
            <div id="product-card" class="card shadow-lg p-4 rounded-lg text-center bg-light border-success">
                <h2 class="mb-3 fw-bold">Dettagli Prodotto</h2>
                
                <!-- Questo div sarà mostrato quando non ci sono prodotti selezionati -->
                <div class="alert alert-warning">Nessun prodotto selezionato.</div>
                
                <!-- Questi elementi saranno inizialmente vuoti ma verranno popolati via JavaScript -->
                <div class="product-details d-none">
                    <div class="product-image-container">
                        <img class="product-image" src="" alt="Immagine prodotto">
                    </div>

                    <p class="fs-5"><strong>ID Prodotto:</strong> <span id="product-id" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>
                    
                    <!--  QUI PRENDE ID   -->
                    <p class="fs-5"><strong>Nome:</strong> <span id="product-name" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>
                    <p class="fs-5"><strong>Categoria:</strong> <span class="product-category text-dark bg-light p-2 border rounded d-inline-block"></span></p>
                    <p class="fs-5"><strong>Data Scadenza:</strong> <span id="product-expiry" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>                    
                    <p class="fs-5"><strong>Quantità:</strong> <span id="product-quantity" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>                    
                    <p class="fs-5"><strong>Unità di misura:</strong> <span id="product-unity" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>                    

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
            </div>
            
            <div id="edit-form" class="card shadow-lg p-4 rounded-lg text-center mt-3 border-primary d-none">
                <h3 class="text-primary fw-bold">Modifica Prodotto</h3>

                <!--  -->
                <!-- QUI NON PRENDE ID!!!!! -->
                <span type="hidden" id="edit-product-id"></span>

                <input type="text" id="edit-name" class="form-control mb-2 fs-5" value="">
                <input type="date" id="edit-expiry" class="form-control mb-2 fs-5" value="">
                
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
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/product_details.js') }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product_details.css') }}">
@endpush