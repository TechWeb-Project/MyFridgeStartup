<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<div class="container mt-4">
    <div class="bg-wrapper">
        <div class="content-container">
            <div id="product-card" class="card shadow-lg p-4 rounded-lg text-center bg-light border-success">
                <h2 id="product-title" class="mb-3 fw-bold">Dettagli Prodotto</h2>
                
                <!-- Questo div sarà mostrato quando non ci sono prodotti selezionati -->
                <div class="alert alert-warning">Nessun prodotto selezionato.</div>
                
                <!-- Questi elementi saranno inizialmente vuoti ma verranno popolati via JavaScript -->
                <div class="product-details d-none">
                    <div class="product-image-container">
                        <img class="product-image" src="" alt="Immagine prodotto">
                    </div>

                    <p class="fs-5"><strong>ID Prodotto:</strong> <span id="product-id" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>
                    
                    <p class="fs-5"><strong>Nome:</strong> <span id="product-name" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>
                    <p class="fs-5"><strong>Categoria:</strong> <span class="product-category text-dark bg-light p-2 border rounded d-inline-block"></span></p>
                    <p class="fs-5"><strong>Data Scadenza:</strong> <span id="product-expiry" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>                    
                    <p class="fs-5"><strong>Quantità:</strong> <span id="product-quantity" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>                    
                    <p class="fs-5"><strong>Unità di misura:</strong> <span id="product-unity" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>                    

                    <div id="delete-confirmation" class="alert alert-danger text-center mt-3 d-none">
                        <p class="mb-3 text-danger fw-bold">Sei sicuro di voler eliminare questo prodotto?</p>
                    </div>

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

            <div id="deleteMessage" class="alert alert-success text-center mt-3 d-none">
                Prodotto eliminato con successo.
            </div>
        </div>
    </div>
</div>
{{-- 
@push('scripts')
    <script src="{{ asset('js/fridge_script.js') }}"></script>
@endpush --}}

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product_details.css') }}">
@endpush