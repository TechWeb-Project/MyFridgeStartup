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
                            <div class="product-card" data-id="{{ $prodotto->id_prodotto }}" data-nome="{{ $prodotto->nome_prodotto }}" data-quantita="{{ $prodotto->quantita }}" data-unita="{{ $prodotto->unita_misura }}">
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
  grid-template-columns: repeat(4, 1fr);
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
    padding: 15px;
    position: relative;
    z-index: 2;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}

/* ---------- Product Image Section ---------- */
.product-img img {
    max-width: 100%;
    height: auto;
    object-fit: contain;
    border-radius: 30%;
    max-width: 80px;
    max-height: 80px;
    object-fit: contain;
}

/* Immagini per le categorie */
.product-category-img {
  width: 40px;
  height: 40px;
  object-fit: contain;
  margin-bottom: 10px;
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
    bottom: -9px;
    right: 0px;
    background-color: rgba(203, 206, 22, 0.07);
    padding: 4px 5px;
    border-radius: 10px;
    color: rgba(180, 124, 4, 0.91);
    font-size: 1rem;
    font-weight: bold;
    text-align: center;
    opacity: 0.85;
    z-index: 5;
}

.quantity-number {
    display: inline-block;
    font-size: 1rem;
    font-weight: 600;
    opacity: 0.75;
}

.quantity-unit {
    font-size: 1rem;
    opacity: 0.7;
    margin-right: 5px;
}

.quantity-number::before {
    content: "x";
    font-weight: bold;
    margin-right: 3px;
    opacity: 0.7;
}

/********Parte animata**********/ 

/* Stili per la modalità di selezione multipla */
.selezione-attiva .product-card {
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.selezione-attiva .product-card:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px rgba(0,0,0,0.3);
}

.product-card {
    perspective: 1000px;           /* Definisce la profondità per gli effetti 3D */
    transform-style: preserve-3d;  /* Mantiene i figli in un contesto 3D */
    overflow: hidden;              /* Nasconde eventuali artefatti durante la transizione */
    will-change: transform;        /* Suggerisce al browser di ottimizzare la trasformazione */
}

.product-card .flipper {
  transition: transform 0.6s ease;
  transform-style: preserve-3d;
  position: relative;
}




/******************************++ */
/* Stile per la card selezionata */
.product-card.selezionato {
    transform: rotateY(180deg);
    background: linear-gradient(135deg, #ffcc00, #ffdd44);
    box-shadow: 0 4px 15px rgba(255, 204, 0, 0.5);
    position: relative;
    border: 2px solid #ffcc00; /* Bordo dorato */
}

/* Retro della card (quando flippata) */
.product-card .product-back {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background:rgb(255, 216, 60);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transform: rotateY(180deg);
    backface-visibility: hidden;
    border-radius: 15px;
    padding: 15px;
}

/* Nome sul retro (solo visibile quando la carta è flippata) */
.product-back .product-name {
    position: absolute; /* Posiziona il nome in alto */
    top: 10px; /* Nome a 10px dal top */
    font-size: 1rem; /* Più piccolo */
    font-weight: bold;
    color: #333;
    text-align: center;
    white-space: nowrap; /* Evita che il nome vada a capo */
    overflow: hidden; /* Nasconde eventuali overflow */
    text-overflow: ellipsis; /* Mostra "..." se il testo è troppo lungo */
    width: 100%; /* Assicura che il testo non esca dai bordi */
    transform: rotateY(180deg);
    opacity: 0; /* Inizialmente invisibile */
    visibility: hidden; /* Impedisce anche l'interazione con l'elemento */
    transition: opacity 0.2s ease, visibility 0.2s ease; /* Animazione per il cambio di opacità e visibilità */
}

.product-card.selezionato .product-back .product-name {
    opacity: 1;
    visibility: visible; /* Rende visibile il nome */
}

/* Spunta grigia senza cerchio verde */
.product-back .checkbox {
    width: 64px; /* Più grande della spunta */
    height: 64px;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #888; /* Grigio per la spunta */
    font-size: 2rem;
    box-shadow: none; /* Rimuove il bordo */
    margin-top: 15px;
    position: absolute;
    top: 50%; /* Centra verticalmente */
    left: 50%; /* Centra orizzontalmente */
    transform: translate(-50%, -50%) rotateY(180deg); /* Compensa il flip */
    opacity: 0; /* Inizialmente invisibile */
    visibility: hidden;
    transition: opacity 0.2s ease, visibility 0.2s ease;
}

.product-card.selezionato .product-back .checkbox {
    opacity: 1;
    visibility: visible;
}


/* Nascondi retro della card quando non selezionata */
.product-card .product-front {
    backface-visibility: hidden;
    transition: transform 0.4s;
}

.product-card.selezionato .product-front {
    transform: rotateY(180deg);
}

.product-card.selezionato .product-back {
    transform: rotateY(0);
} 

/* Stile per il bottone di selezione */
#selezione_button {
    background: rgba(255, 212, 69, 0.88); /* Giallo dorato */  
    color: rgb(252, 249, 249);
    border: none;
    padding: 10px 20px;
    font-size: 1.2rem;
    border-radius: 8px;
    transition: background 0.3s, color 0.3s;
    font-weight: bold;
    margin-bottom: 10px;
}

#selezione_button.attivo {
    background: rgb(250, 230, 150); /* Giallo dorato più chiaro e tenue */
    cursor: pointer;
}

#selezione_button.attivo::after {
    content: " ✖";
    color: red;
    font-weight: bold;
}

#selezione_button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Bottone "Inizia a cucinare" */
#start-cooking:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

#select-button {
    background-color: grey;
    color: white;
    cursor: not-allowed;
}

#select-button.active {
    background-color: #ff5252; /* Rosso o altro colore */
    cursor: pointer;
}


</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    let selectionMode = false; // Inizia disattivato
    let selectedProducts = new Map(); // Mappa per prodotti selezionati
    const SelezioneBtn = document.getElementById("selezione_button");
    const startCookingBtn = document.getElementById("start-cooking");
    const fridgeContainer = document.querySelector(".fridge");

    // Inizializza il bottone "Seleziona Prodotti"
    SelezioneBtn.classList.add("disattivato"); // Colore grigio
    SelezioneBtn.textContent = "Seleziona Prodotti";

    SelezioneBtn.addEventListener("click", () => {
        selectionMode = !selectionMode; // Alterna la modalità selezione

        if (selectionMode) {
            SelezioneBtn.textContent = "Annulla selezione";
            SelezioneBtn.classList.remove("disattivato");
            SelezioneBtn.classList.add("attivo");
        } else {
            SelezioneBtn.textContent = "Seleziona Prodotti";
            SelezioneBtn.classList.remove("attivo");
            SelezioneBtn.classList.add("disattivato");

            // Se si annulla la selezione, svuota la lista dei prodotti selezionati
            selectedProducts.clear();
            document.querySelectorAll(".product-card").forEach(card => {
                card.classList.remove("selezionato");
            });

            updateButtonState(); // Aggiorna lo stato del bottone startCooking
        }
    });

    fridgeContainer.addEventListener("click", (event) => {
        let card = event.target.closest(".product-card");
        if (!card) return;

        if (!selectionMode) {
            console.log("Le card faranno altro in futuro");
            return; // Se non in modalità selezione, le card non si flippano
        }

        const id = card.dataset.id;
        const nome = card.dataset.nome;
        const quantita = card.dataset.quantita;
        const unita = card.dataset.unita;
        const isSelected = selectedProducts.has(id);

        if (isSelected) {
            selectedProducts.delete(id);
            card.classList.remove("selezionato");
        } else {
            selectedProducts.set(id, { id, nome, quantita, unita });
            card.classList.add("selezionato");
        }

        updateButtonState();
    });

    function updateButtonState() {
        startCookingBtn.disabled = selectedProducts.size === 0;
        startCookingBtn.style.opacity = selectedProducts.size === 0 ? "0.5" : "1";
    }

    startCookingBtn.addEventListener("click", () => {
        if (selectedProducts.size > 0) {
            let message = "localhost dice:\n";
            selectedProducts.forEach(p => {
                message += `- ${p.nome}: ${p.quantita} ${p.unita}\n`;
            });
            alert(message);
        }
    });
});

</script>
