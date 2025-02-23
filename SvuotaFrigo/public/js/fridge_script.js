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

            //RIMOZIONE BUG //
            //
            document.querySelectorAll(".product-card").forEach(card => {
                card.classList.remove("selezionato");
            });

            updateButtonState(); // Aggiorna lo stato del bottone startCooking
        }
    });

    //Richiesta per Product_detail
    //
    let selectedCard = null; // Variabile per tenere traccia della card selezionata in modalità singola

    fridgeContainer.addEventListener("click", (event) => {
        let card = event.target.closest(".product-card");

        // Se clicchi dentro il div del frigo ma NON su una card,
        // deseleziona eventuale card selezionata
        if (!card) {
            if (selectedCard) {
                selectedCard.classList.remove("singola-selezionata");
                selectedCard = null;
            }
            return;
        }

        if (!selectionMode) {
            // Se clicchi sulla stessa card già selezionata, deselezionala
            if (selectedCard === card) {
                selectedCard.classList.remove("singola-selezionata");
                selectedCard = null;
                return;
            }

            // Se c'era un'altra card selezionata, deselezionala
            if (selectedCard) {
                selectedCard.classList.remove("singola-selezionata");
            }

            // Se clicchi su una nuova card, selezionala e invia la richiesta AJAX
            selectedCard = card;
            selectedCard.classList.add("singola-selezionata");

            // Dati da inviare al server
            const id = card.dataset.id;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Nuova richiesta AJAX
            fetch('/product_details', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Aggiorna i dettagli del prodotto
                    document.getElementById('product-name').textContent = data.product.nome;
                    document.getElementById('product-expiry').textContent = data.product.data_scadenza;
                    document.querySelector('.product-category').textContent = data.product.categoria;
                    
                    // Mostra l'immagine del prodotto
                    const productImage = document.querySelector('.product-image');
                    const productDetails = document.querySelector('.product-details');
                    
                    if (data.product.image) {
                        productImage.src = data.product.image; // Usa l'URL completo
                        productImage.style.display = 'block';
                        document.querySelector('.product-image-container').style.display = 'block';
                    } else {
                        productImage.style.display = 'none';
                        document.querySelector('.product-image-container').style.display = 'none';
                    }

                    // Mostra i dettagli e nascondi l'avviso
                    document.querySelector('.alert-warning').style.display = 'none';
                    productDetails.classList.remove('d-none');
                }
            })
            .catch(error => console.error('Errore:', error));

            return; // Esci se non sei in modalità selezione multipla
        }


        // Se è in modalità selezione multipla, continua come prima
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

    // Aggiungi un listener per i click su tutto il documento
    // che deseleziona la card anche se si clicca dentro il div ma fuori da una card
    document.addEventListener("click", (event) => {
        if (selectedCard && !event.target.closest(".product-card")) {
            selectedCard.classList.remove("singola-selezionata");
            selectedCard = null;
        }
    });

    ////////////////////////////////////////////////////////////////////////7
    function updateButtonState() {
        startCookingBtn.disabled = selectedProducts.size === 0;
        startCookingBtn.style.opacity = selectedProducts.size === 0 ? "0.5" : "1";
    }

    startCookingBtn.addEventListener("click", () => {
        if (selectedProducts.size > 0) {
            // Converti i prodotti selezionati in un array di nomi
            const selectedIngredients = Array.from(selectedProducts.values())
                .map(product => product.nome)
                .join(', ');

            // Aggiorna gli ingredienti nel generatore di ricette
            const fridgeIngredientsInput = document.getElementById('fridge_ingredients');
            fridgeIngredientsInput.value = selectedIngredients;

            // Aggiorna i badge degli ingredienti
            const selectedIngredientsSpan = document.getElementById('selected_ingredients');
            if (selectedIngredientsSpan) {
                const ingredientsList = selectedIngredients.split(',')
                    .map(ingredient => ingredient.trim())
                    .map(ingredient => `<span class="badge bg-primary me-1">${ingredient}</span>`)
                    .join(' ');
                
                selectedIngredientsSpan.innerHTML = ingredientsList;
            }

            // Scorri alla sezione delle ricette
            document.getElementById('recipes_generator').scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        }
    });

    fridgeContainer.addEventListener("click", (event) => {
        let card = event.target.closest(".product-card");
        if (!card) return;

        const productId = card.dataset.id;
        const productImage = card.dataset.image; // Aggiungi questo
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Nascondi l'avviso di "nessun prodotto selezionato"
        document.querySelector('.alert-warning').style.display = 'none';
        
        // Mostra i dettagli del prodotto
        document.querySelector('.product-details').classList.remove('d-none');

        fetch('/product_details', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ 
                id: productId,
                imageName: productImage // Passa l'immagine al controller
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('product-name').textContent = data.product.nome;
                document.getElementById('product-expiry').textContent = data.product.data_scadenza;
                document.querySelector('.product-category').textContent = data.product.categoria;
                
                const productImageElement = document.querySelector('.product-image');
                if (data.product.immagine) {
                    productImageElement.src = data.product.immagine;
                    productImageElement.style.display = 'block';
                    document.querySelector('.product-image-container').style.display = 'block';
                }
            }
        })
        .catch(error => console.error('Errore:', error));
    });
});