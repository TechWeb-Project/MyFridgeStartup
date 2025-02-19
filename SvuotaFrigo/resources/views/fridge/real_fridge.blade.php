<div class="bg-light p-4 rounded-lg m-2 flex-grow-1 fridge">
    <div class="door">
        <div class="handle"></div>
        <div class="shelves">
            @foreach($prodotti->chunk(4) as $shelf)
                <div class="shelf">
                    <div class="products-container">
                        @foreach($shelf as $prodotto)
                            <div class="product-card">
                                <div class="product-img">
                                    <!-- Immagine del prodotto (categoria) -->
                                    <img src="{{ asset('images/icone_frigo/' . $prodotto->immagine) }}" alt="{{ $prodotto->categoriaDurata->categoria->nome_categoria ?? 'Prodotto' }}">
                                    <!-- Bollino di scadenza -->
                                    <span class="expiration-dot {{ $prodotto->dotClass }}"></span>
                                </div>
                                <div class="product-name text-truncate fw-bold fs-6 d-block">
                                    {{ $prodotto->nome_prodotto }}
                                </div>
                                <div class="quantity-badge">
                                    <span class="quantity-number">{{ $prodotto->quantita }}</span>
                                    <span class="quantity-unit">{{ $prodotto->unita_misura }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>





<style> 
/* ---------- Fridge Container ---------- */
.fridge {
  position: relative;
  background: linear-gradient(to bottom, #e0f7ff, #a6ddf0);
  border-radius: 20px;
  padding: 20px;
  box-shadow: inset 0 15px 30px rgba(0, 0, 0, 0.2),
              inset 0 -15px 30px rgba(0, 0, 0, 0.1),
              0 4px 8px rgba(0, 0, 0, 0.3);
  perspective: 800px;
}

/* Effetto luce in cima al frigo */
.fridge::before {
  content: "";
  position: absolute;
  top: 20px;
  left: 50%;
  width: 75%;
  height: 40px;
  background: radial-gradient(circle, rgba(255,255,255,1) 0%, rgba(200,240,255,0.8) 30%, rgba(150,220,255,0.5) 60%, rgba(255,255,255,0) 100%);
  transform: translateX(-50%);
  filter: blur(8px);
  opacity: 1;
  z-index: 1;
}

/* ---------- Door e Handle ---------- */
/* ---------- Door ---------- */
.door {
  background: #f0faff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
  position: relative;
}

.handle {
  width: 10px;
  height: 100px;
  background: #ccc;
  border-radius: 5px;
  position: absolute;
  right: -15px;
  top: 50%;
  transform: translateY(-50%);
  box-shadow: 0 2px 2px rgba(0,0,0,0.2);
}

/* ---------- Shelves ---------- */
.shelves {
  margin-top: 20px;
}

/* Ogni scaffale come una card Bootstrap personalizzata */
.shelf {
  background: rgba(220,240,255,0.95);
  margin-bottom: 20px;
  padding: 25px;
  border-radius: 12px;
  box-shadow: inset 0 0 15px rgba(0,0,0,0.08), inset 0 -10px 20px rgba(0,0,0,0.05);
  position: relative;
  z-index: 1;
  overflow: hidden;
}

.shelf::before {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 50%;
  background: rgba(166,207,238,0.85);
  border-radius: 10px;
  box-shadow: inset 0 10px 15px rgba(0,0,0,0.15), inset 0 -10px 15px rgba(0,0,0,0.1);
  transform: perspective(200px) rotateX(3deg);
  z-index: 1;
}

.shelf::after {
  content: "";
  position: absolute;
  bottom: -5px;
  left: 0;
  width: 100%;
  height: 5px;
  background: rgba(0,0,0,0.05);
  border-radius: 10px;
}

/* ---------- Products Grid ---------- */
.products-container {
  display: grid;
  grid-template-columns: repeat(4, 1fr); /* 4 card per fila */
  gap: 20px;
  padding: 20px;
}

/* ---------- Product Card ---------- */
.product-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    text-align: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    padding: 15px;  /* Aggiungi padding per distanziare l'immagine dal bordo */
    position: relative;
    z-index: 2;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}

/* ---------- Product Image Section ---------- */
.product-img img {
    max-width: 100%;  /* Rende l'immagine responsiva */
    height: auto;     /* Mantiene le proporzioni dell'immagine */
    object-fit: contain; /* Adatta l'immagine al contenitore senza distorsioni */
    border-radius: 30%;  /* Se vuoi mantenerla rotonda, puoi usare questa regola */
    max-width: 80px;  /* Imposta la larghezza massima desiderata */
    max-height: 80px; /* Imposta l'altezza massima desiderata */
    object-fit: contain;  /* Mantiene le proporzioni */
}

/* Immagini per le categorie */
.product-category-img {
  width: 40px;  /* Dimensione dell'immagine della categoria */
  height: 40px;
  object-fit: contain; /* Mantieni le proporzioni */
  margin-bottom: 10px; /* Distanza tra l'immagine e il bollino */
}

/* ---------- Expiration Dot ---------- */
.expiration-dot {
  position: absolute;
  top: 10px;
  left: 10px;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  border: 2px solid #fff;
  box-shadow: 0 0 6px rgba(0,0,0,0.2);
  z-index: 3;
}

/* Stati di scadenza */
.dot-green { background-color: rgba(40,167,69,1); }
.dot-orange { background-color: rgba(255,193,7,1); }
.dot-red { background-color: rgba(220,53,69,1); }

/* ---------- Product Name ---------- */
.product-name {
    margin-top: 10px;
    font-family: 'Roboto', sans-serif;
    font-size: 1.1rem;
    font-weight: 500;
    color: #333;
    padding: 0 10px;
    text-transform: capitalize;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    max-width: 100%;
    word-break: break-word;
}

/* ---------- Quantita ---------- */
.quantity-badge {
    position: absolute;
    bottom: -9px; /* Posizione giusta */
    right: 0px;
    background-color: rgba(203, 206, 22, 0.07);
    padding: 4px 5px;
    border-radius: 10px;
    color: rgba(180, 124, 4, 0.91); 
    font-size: 1rem;  /* Più piccolo */
    font-weight: bold;
    text-align: center;
    opacity: 0.85;
}

.quantity-number {
    display: inline-block;
    font-size: 1rem;  /* Ridotto per essere più discreto */
    font-weight: 600;
    opacity: 0.75;
}

.quantity-unit {
    font-size: 1rem;  /* Ridotto */
    opacity: 0.7;
    margin-right: 5px; /* Spazio tra unità e quantità */
}

.quantity-number::before {
    content: "x";
    font-weight: bold;
    margin-right: 3px;
    opacity: 0.7;
}


</style>