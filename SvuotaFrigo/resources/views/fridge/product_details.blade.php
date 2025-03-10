<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<div class="container mt-4">
    <div class="bg-wrapper">
        <div class="flip-container">
            <button id="flip" class="flip-button btn btn-primary">
                <span class="front-text">Visualizza Dettagli</span>
                <span class="back-text" style="display: none;">Aggiungi Prodotto</span>
            </button>
            
            <div class="content-container">
                <div id="aggiungi-prodotto" class="card shadow-lg p-4 rounded-lg text-center bg-light border-cust">
                    <h2 id="product-title" class="mb-3 fw-bold">Aggiungi Prodotto</h2>
                    

                    <form id="addProductForm" >
   
                        <!-- Nome alimento -->
                        <div class="form-aggiungi">
                            <label for="nome_prodotto"><strong>Nome alimento</strong></label>
                            <input type="text" name="nome_prodotto" id="nome_prodotto" class="form-control" required style="height: 35px;">
                        </div>

                        <!-- Categoria -->
                        <div class="form-aggiungi">
                            <label for="categoria_id"><strong>Categoria</strong></label>
                            <select name="categoria_id" id="categoria_id" class="form-control" required style="height: 35px;">
                                @foreach (App\Models\Categoria::all() as $categoria)
                                    <option value="{{ $categoria->id_categoria }}">{{ $categoria->nome_categoria }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Durata -->
                        <div class="form-aggiungi">
                            <label for="durata_id"><strong>Durata</strong></label>
                            <select name="durata_id" id="durata_id" class="form-control" required style="height: 35px;">
                                @foreach (App\Models\Durata::all() as $durata)
                                    <option value="{{ $durata->id_durata }}">{{ $durata->nome_durata }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantità -->
                        <div class="form-aggiungi">
                            <label for="quantita"><strong>Quantità</strong></label>
                            <input type="number" name="quantita" id="quantita" class="form-control" required style="height: 35px;" min="1" max="5000">
                        </div>

                        <!-- Unità di Misura -->
                        <div class="form-aggiungi" style="margin-left: +100px;">
                            <label for="unita_misura"><strong>Unità di misura</strong></label>
                            <select name="unita_misura" id="unita" class="form-control" required style="height: 35px;">
                                @foreach (App\Constants\UnitaMisura::all() as $unita)
                                    <option value="{{ $unita }}">{{ ucfirst($unita) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="clear: both;"></div>
                        
                        <!-- Pulsante di invio -->
                        <div style="clear: both;"></div>
                        <div class="button-container">
                            <button id="addButton" type="submit" class="plusButton" title="Aggiungi Alimento">
                                    
                                <span>Aggiungi</span>

                            </button>
                        </div>
                    </form>
                                    

                    
                </div>

                <div id="product-card" class="card shadow-lg p-4 rounded-lg text-center bg-light border-cust">
                    <h2 id="product-title" class="mb-3 fw-bold">Dettagli Prodotto</h2>
                
                    <!-- Questo div sarà mostrato quando non ci sono prodotti selezionati -->
                    <div class="alert alert-warning product-details-format">
                        <!-- <div class="placeholder-container">
                            <div class="gray-placeholder"></div>
                        </div> -->
                        <div class="details-container">
                            <p class="fs-5"><strong>Nome:</strong> <span>...</span></p>
                            <p class="fs-5"><strong>Categoria:</strong> <span>...</span></p>
                            <p class="fs-5"><strong>Data Scadenza:</strong> <span>...</span></p>
                            <p class="fs-5"><strong>Quantità:</strong> <span>...</span></p>
                            <p class="fs-5"><strong>Unità di misura:</strong> <span>...</span></p>
                        </div>
                    </div>
                
                    <!-- Questi elementi saranno inizialmente vuoti ma verranno popolati via JavaScript -->
                    <div class="product-details d-none">
                        <!-- <div class="product-image-container">
                            <img class="product-image" src="" alt="Immagine prodotto">
                        </div> -->
                        <span id="product-id" class="text-dark bg-light p-2 border rounded d-inline-block" style="display: none !important;"></span>
                    
                        <p><strong>Nome:</strong> <span id="product-name" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>
                        <p><strong>Categoria:</strong> <span class="product-category text-dark bg-light p-2 border rounded d-inline-block"></span></p>
                        <p><strong>Data Scadenza:</strong> <span id="product-expiry" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>                    
                        <p><strong>Quantità:</strong> <span id="product-quantity" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>                    
                        <p><strong>Unità di misura:</strong> <span id="product-unity" class="text-dark bg-light p-2 border rounded d-inline-block"></span></p>                    

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
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product_details.css') }}">
@endpush