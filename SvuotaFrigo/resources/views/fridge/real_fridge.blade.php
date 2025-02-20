<!-- Container per i bottoni sopra il frigo -->
<div style="text-align: center; margin-bottom: 10px;">
  <button id="selezione_button" class="btn btn-primary">Seleziona Prodotti</button>
  <button id="start-cooking" class="btn btn-success" disabled>Inizia a cucinare</button>
</div>

<!-- Fridge Container -->
<div class="bg-light p-4 rounded-lg m-2 flex-grow-1 fridge">
    <div class="door">
        <div class="handle"></div>
        <div class="shelves">
            @foreach($prodotti->chunk(4) as $shelf)
                <div class="shelf">
                    <div class="products-container">
                        @foreach($shelf as $prodotto)
                            <div class="product-card" data-id="{{ $prodotto->id_prodotto }}" data-nome="{{ $prodotto->nome_prodotto }}" data-quantita="{{ $prodotto->quantita }}" 
                            data-unita="{{ $prodotto->unita_misura }}" data-scadenza="{{ $prodotto->data_scadenza }}" data-immagine="{{ asset('storage/'.$prodotto->immagine) }}">
                                <div class="product-front">
                                    <div class="product-img">
                                        <img src="{{ asset('images/icone_frigo/' . $prodotto->immagine) }}" alt="{{ $prodotto->categoriaDurata->categoria->nome_categoria ?? 'Prodotto' }}">
                                        <span class="expiration-dot {{ $prodotto->dotClass }}"></span>
                                    </div>
                                    <div class="product-name text-truncate fw-bold fs-6 d-block">{{ $prodotto->nome_prodotto }}</div>
                                    <div class="quantity-badge">
                                        <span class="quantity-number">{{ $prodotto->quantita }}</span>
                                        <span class="quantity-unit">{{ $prodotto->unita_misura }}</span>
                                    </div>
                                </div>
                                <div class="product-back">
                                    <span class="product-name">{{ $prodotto->nome_prodotto }}</span>
                                    <div class="checkbox">✔</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('styles')
    <!-- Link al file CSS -->
    <link rel="stylesheet" href="{{ asset('css/fridge_style.css') }}">
@endpush

@push('scripts')
    <!-- Script JS -->
    <script src="{{ asset('js/fridge_script.js') }}"></script>
@endpush


