<div class="bg-light p-4 rounded-lg m-2 flex-grow-1 fridge">
    <div class="door">
        <div class="handle"></div>
        <div class="shelves">
            @foreach($prodotti->chunk(4) as $shelf)
                <div class="shelf">
                    <div class="products-container">
                        @foreach($shelf as $prodotto)
                            @php
                                $now = \Carbon\Carbon::now();
                                $expDate = \Carbon\Carbon::parse($prodotto->data_scadenza);
                                $daysDiff = (int)$expDate->diffInDays($now, false);
                                if($expDate->lt($now)) {
                                    $dotClass = 'dot-red';
                                } elseif($daysDiff < 3) {
                                    $dotClass = 'dot-orange';
                                } else {
                                    $dotClass = 'dot-green';
                                }
                            @endphp
                            <div class="product-card">
                                <div class="product-img">
                                    <span class="apple">🍎</span>
                                    <span class="expiration-dot {{ $dotClass }}"></span>
                                </div>
                                <div class="product-name">
                                    {{ $prodotto->nome_prodotto }}
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
  /* Utilizzo di box-shadow custom */
  box-shadow: inset 0 0 15px rgba(0,0,0,0.08),
              inset 0 -10px 20px rgba(0,0,0,0.05);
  position: relative;
  z-index: 1;
  overflow: hidden;
}

/* Pseudo-elemento per simulare la base dello scaffale */
.shelf::before {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 50%;
  background: rgba(166,207,238,0.85);
  border-radius: 10px;
  box-shadow: inset 0 10px 15px rgba(0,0,0,0.15),
              inset 0 -10px 15px rgba(0,0,0,0.1);
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
/* Usa le utilità Bootstrap per la griglia */
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
  padding-bottom: 15px;
  position: relative;
  z-index: 2; /* Stare sopra lo shelf */
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}

/* ---------- Product Image Section ---------- */
.product-img {
  position: relative;
  background: #f0faff;
  border-bottom: 1px solid #e0e0e0;
  padding: 20px;
  border-top-left-radius: 15px;
  border-top-right-radius: 15px;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* Emoji Mela */
.apple {
  font-size: 3rem;
}

/* Bollino di scadenza come badge in alto a destra */
.expiration-dot {
  position: absolute;
  top: 10px;
  right: 10px;
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
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}


</style>