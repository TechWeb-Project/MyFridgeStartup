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
                <div id="aggiungi-prodotto" class="card shadow-lg p-4 rounded-lg text-center bg-light border-success">
                    <h2 id="product-title" class="mb-3 fw-bold">Aggiungi Prodotto</h2>

                    <form id="addProductForm" >
                        
                        <div style="display: inline-block; width: 30%; margin-right: 10px;">
                            <label for="nome"><strong>Nome alimento</strong></label>
                            <input type="text" name="nome_prodotto" id="nome_prodotto" class="form-control" required style="height: 35px;">
                        </div>
                        
                        <div style="display: inline-block; width: 30%; margin-right: 10px;">
                            <label for="categoria_id"><strong>Categoria</strong></label>
                            <select name="categoria_id" id="categoria_id" class="form-control" required style="height: 35px;">
                                <option value="1">Latticino</option>
                                <option value="2">Carne</option>
                                <option value="3">Pesce</option>
                                <option value="4">Verdura</option>
                                <option value="5">Frutta</option>
                                <option value="6">Legume</option>
                                <option value="7">Cereale</option>
                                <option value="8">Pane</option>
                                <option value="9">Prodotto Forno</option>
                                <option value="10">Bevanda</option>
                                <option value="11">Conserva</option>
                                <option value="12">Condimento</option>
                                <option value="13">Dolce</option>
                                <option value="14">Proteina vegetale</option>
                                <option value="15">Snack</option>
                              </select>
                        </div>
                        
                        <div style="display: inline-block; width: 30%; margin-right: 10px;">
                            <label for="durata_id"><strong>Durata</strong></label>
                            <select name="durata_id" id="durata_id" class="form-control" required style="height: 35px;">
                                <option value="1">breve durata</option>
                                <option value="2">media durata</option>
                                <option value="3">lunga durata</option>
                              </select>
                        </div>
                        
                        <div style="display: inline-block; width: 30%; margin-right: 10px;">
                            <label for="unita"><strong>Unità di misura</strong></label>
                            <select name="unita" id="unita" class="form-control" required style="height: 35px;">
                                <option value="pezzi">Pezzi</option>
                                <option value="grammi">Grammi</option>
                                <option value="fette">Fette</option>
                                <option value="ml">Ml</option>

                              </select>
                        </div> 

                        <div style="display: inline-block; width: 30%; margin-right: 10px;">
                            <label for="quantita">Quantità</label>
                            <input type="number" name="quantita" id="quantita" class="form-control" required style="height: 35px;" min="1" max="5000">
                        </div>
                        
                        <div style="clear: both;"></div>
                        <div class="button-container">
                            <button id="addButton" type="submit" class="plusButton" title="Aggiungi Alimento">
                                <svg class="plusIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30">
                                    <g mask="url(#mask0_21_345)">
                                        <path d="M13.75 23.75V16.25H6.25V13.75H13.75V6.25H16.25V13.75H23.75V16.25H16.25V23.75H13.75Z"></path>
                                    </g>
                                </svg>
                                
                            </button>
                        </div>
                        </form>
                

                    
                </div>

                <div id="product-card" class="card shadow-lg p-4 rounded-lg text-center bg-light border-success">
                    <h2 id="product-title" class="mb-3 fw-bold">Dettagli Prodotto</h2>
                
                    <!-- Questo div sarà mostrato quando non ci sono prodotti selezionati -->
                    <div class="alert alert-warning product-details-format">
                        <div class="placeholder-container">
                            <div class="gray-placeholder"></div>
                        </div>
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
                        <div class="product-image-container">
                            <img class="product-image" src="" alt="Immagine prodotto">
                        </div>
                        <span id="product-id" class="text-dark bg-light p-2 border rounded d-inline-block" style="display: none !important;"></span>
                    
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
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product_details.css') }}">
@endpush