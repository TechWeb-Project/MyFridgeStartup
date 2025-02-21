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
            const immagine = card.dataset.immagine;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');;
            
            console.log(csrfToken);

            fetch('/product_details', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ id: id })
            })
            // .then(response => response.json())
            // .then(data => {
            //     // Aggiorna il div 'product_details' con i dati ricevuti
            //     document.querySelector('#product_details .product-name').textContent = data.nome;
            //     document.querySelector('#product_details .product-quantity').textContent = `${data.quantita} ${data.unita}`;
            //     document.querySelector('#product_details .product-expiry').textContent = data.scadenza;
            //     document.querySelector('#product_details .product-image').src = data.immagine;
            // })
            .catch(error => console.error('Errore nella richiesta:', error));

            // Chiamata AJAX per inviare i dettagli al server
            // fetch('/get-product-details', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            //     },
            //     body: JSON.stringify({ id, immagine })
            // })
            // .then(response => response.json())
            // .then(data => {
            //     // Aggiorna il div 'product_details' con i dati ricevuti
            //     document.querySelector('#product_details .product-name').textContent = data.nome;
            //     document.querySelector('#product_details .product-quantity').textContent = `${data.quantita} ${data.unita}`;
            //     document.querySelector('#product_details .product-expiry').textContent = data.scadenza;
            //     document.querySelector('#product_details .product-image').src = data.immagine;
            // })
            // .catch(error => console.error('Errore nella richiesta:', error));

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
            // Invia i dati dei prodotti selezionati via AJAX
            fetch('/get-recipes', { // Assicurati che l'endpoint sia corretto ////*************************************/////
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ products: Array.from(selectedProducts.values()) })
            })
            .then(response => response.json())
            .then(data => {
                // Aggiorna il div "recipes_generator" con i contenuti ricevuti dal server
                // Assumiamo che il server restituisca un oggetto { recipesHTML: "<p>...</p>" }
                document.querySelector('#recipes_generator').innerHTML = data.recipesHTML;
            })
            .catch(error => console.error('Errore nella richiesta:', error));
        }
    });
});