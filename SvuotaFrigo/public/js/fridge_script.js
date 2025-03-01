document.addEventListener("DOMContentLoaded", () => {
    let selectionMode = false; // Inizia disattivato
    let selectedProducts = new Map(); // Mappa per prodotti selezionati
    let isEditing = false;
    let isDeleting = false;
    let originalValues = {};
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
            //se sei in modalità modifica, esci dalla modalità
            if(isEditing)
            {
                exitEditMode();
            }
            //se sei in modalità eliminazione, nascondi il messaggio di conferma
            if(isDeleting)
            {
                hideDeleteConfirmation();
            }
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

                    // document.getElementById('product-name').textContent = data.product.nome;
                    // document.getElementById('product-expiry').textContent = data.product.data_scadenza;
                    // document.querySelector('.product-category').textContent = data.product.categoria;
                    
                    // Salva l'ID nel campo nascosto e lo mostra nel div
                    document.getElementById('product-id').textContent = data.product.id;


                    // Mostra l'immagine del prodotto
                    const productImage = document.querySelector('.product-image');
                    const productDetails = document.querySelector('.product-details');
                    
                    if (data.product.immagine) {
                        productImageElement.src = data.product.immagine;
                        productImageElement.style.display = 'block';
                        document.querySelector('.product-image-container').style.display = 'block';
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
            //se sei in modalità modifica, esci dalla modalità
    if(isEditing)
        {
                exitEditMode();
        }
        //se sei in modalità eliminazione, nasc                             ondi il messaggio di conferma
        if(isDeleting)
        {
            hideDeleteConfirmation();
        }
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
                document.getElementById('product-id').textContent = data.product.id;
                document.getElementById('product-name').textContent = data.product.nome;
                document.getElementById('product-expiry').textContent = data.product.data_scadenza;
                document.querySelector('.product-category').textContent = data.product.categoria;
                document.getElementById('product-quantity').textContent = data.product.quantita;
                document.getElementById('product-unity').textContent = data.product.unita;

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


    // Modifiche aggiornamento notifica di cambio prodotto

    document.addEventListener('productUpdated', function(e) {
        const updatedProduct = e.detail; 
        const productCard = document.querySelector(`[data-id='${updatedProduct.id}']`);
    
    
        if(productCard) {
            // Selettori aggiornati secondo il template Blade
            const nameElem = productCard.querySelector(".product-name"); 
            const expiryDot = productCard.querySelector(".expiration-dot");
            const quantityNumber = productCard.querySelector(".quantity-number");
            const quantityUnit = productCard.querySelector(".quantity-unit");
    
            if(nameElem) {
                nameElem.textContent = updatedProduct.nome; 
                nameElem.classList.add('animate-update'); 
            }
    
            if(expiryDot) {
                // Calcola i giorni rimanenti alla scadenza
                const expiryDate = new Date(updatedProduct.data_scadenza.split('/').reverse().join('-'));
                const currentDate = new Date();
                const timeDiff = expiryDate - currentDate;
                const daysDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
    
                // Rimuovi le classi esistenti
                expiryDot.classList.remove('dot-green', 'dot-orange', 'dot-red');
                
                // Aggiungi la nuova classe con animazione
                if (daysDiff <= 0) {
                    expiryDot.classList.add('dot-red', 'animate-dot');
                } else if (daysDiff <= 2) {
                    expiryDot.classList.add('dot-orange', 'animate-dot');
                } else {
                    expiryDot.classList.add('dot-green', 'animate-dot');
                }
            }
    
            if(quantityNumber) {
                quantityNumber.textContent = updatedProduct.quantita;
                quantityNumber.classList.add('animate-update');
            }
    
            if(quantityUnit) {
                quantityUnit.textContent = updatedProduct.unita;
                quantityUnit.classList.add('animate-update');
            }
    
            // Rimuovi le classi di animazione dopo che sono terminate
            setTimeout(() => {
                productCard.querySelectorAll('.animate-update').forEach(el => {
                    el.classList.remove('animate-update');
                });
                if(expiryDot) {
                    expiryDot.classList.remove('animate-dot');
                }
            }, 500);
        } else {
            console.error("Product card non trovata per l'id:", updatedProduct.id);
        }
    });

    document.addEventListener('productDeleted', function(e) {
        const deletedProduct = e.detail;
        const productCard = document.querySelector(`[data-id='${deletedProduct.id}']`);

        if (productCard) {
            const shelves = Array.from(document.querySelectorAll('.shelf'));
            const currentShelf = productCard.closest('.shelf');
            const currentShelfIndex = shelves.indexOf(currentShelf);
            
            // Trova tutte le card rimanenti dopo quella eliminata
            const allRemainingCards = [];
            shelves.forEach((shelf, index) => {
                if (index >= currentShelfIndex) {
                    const cards = Array.from(shelf.querySelectorAll('.product-card'));
                    allRemainingCards.push(...cards);
                }
            });

            const deletedIndex = allRemainingCards.indexOf(productCard);
            const cardsToMove = allRemainingCards.slice(deletedIndex + 1);

            // Aggiungi l'animazione di eliminazione
            productCard.classList.add('animate-delete');

            // Gestisci l'animazione delle card successive
            cardsToMove.forEach((card, index) => {
                const isLastInShelf = (index + deletedIndex) % 4 === 3;
                if (isLastInShelf) {
                    card.style.zIndex = "10"; // Assicura che la card sia sopra le altre durante l'animazione
                    card.classList.add('animate-shelf-change');
                } else {
                    card.classList.add('animate-slide-left');
                }
            });

            // Riorganizza le card dopo l'animazione
            setTimeout(() => {
                productCard.remove();
                cardsToMove.forEach((card, index) => {
                    card.classList.remove('animate-slide-left', 'animate-shelf-change');
                    card.style.zIndex = ""; // Ripristina il z-index
                    const targetShelfIndex = Math.floor((index + deletedIndex) / 4);
                    const targetShelf = shelves[targetShelfIndex];
                    if (targetShelf) {
                        targetShelf.querySelector('.products-container').appendChild(card);
                    }
                });
            }, 1000); // Aumentato il timeout per corrispondere alla durata dell'animazione
        }
    });

    //////////////////////////////////////////////////////////////////////////////////////////////////////
    ///product_details.js

    const editButton = document.getElementById('edit-btn');
    const deleteButton = document.getElementById('deleteProductBtn');
    const saveButton = document.createElement('button');
    const cancelButton = document.createElement('button');
    const confirmDeleteButton = document.createElement('button');
    const cancelDeleteButton = document.createElement('button');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    saveButton.id = 'save-btn';
    saveButton.className = 'btn custom-btn';
    saveButton.textContent = 'Salva';

    cancelButton.id = 'cancel-btn';
    cancelButton.className = 'btn btn-secondary';
    cancelButton.textContent = 'Annulla';

    confirmDeleteButton.id = 'confirm-delete-btn';
    confirmDeleteButton.className = 'btn btn-danger';
    confirmDeleteButton.textContent = 'Sì';

    cancelDeleteButton.id = 'cancel-delete-btn';
    cancelDeleteButton.className = 'btn btn-secondary';
    cancelDeleteButton.textContent = 'No';

    function formatDateForInput(dateStr) {
        const [day, month, year] = dateStr.split('/');
        return `${year}-${month}-${day}`;
    }

    function formatDateForDisplay(dateStr) {
        const [year, month, day] = dateStr.split('-');
        return `${day}/${month}/${year}`;
    }

    //modifica

    function enterEditMode() {
        isEditing = true;
        document.getElementById('product-title').textContent = 'Modifica Prodotto';
    
        // Memorizza i valori originali
        originalValues = {
            nome: document.getElementById('product-name').textContent.trim(),
            scadenza: document.getElementById('product-expiry').textContent.trim(),
            quantita: document.getElementById('product-quantity').textContent.trim(),
            unita: document.getElementById('product-unity').textContent.trim()
        };
        
    
        // Modifica gli elementi con i campi di input
        document.getElementById('product-name').innerHTML = `<input type="text" id="edit-name" class="form-control" value="${originalValues.nome}">`;
        document.getElementById('product-expiry').innerHTML = `<input type="date" id="edit-expiry" class="form-control" value="${formatDateForInput(originalValues.scadenza)}" readonly>`;
        document.getElementById('product-quantity').innerHTML = `<input type="number" id="edit-quantity" class="form-control" value="${originalValues.quantita}">`;
        document.getElementById('product-unity').innerHTML = `<input type="text" id="edit-unity" class="form-control" value="${originalValues.unita}">`;
    
        // Permetti la modifica della data al clic
        document.getElementById('edit-expiry').addEventListener('click', function () {
            this.removeAttribute('readonly');
        });
    
        // Sostituzione dei bottoni
        editButton.replaceWith(saveButton);
        deleteButton.replaceWith(cancelButton);
    }
    

    function exitEditMode(updatedData = null) {
        isEditing = false;
        document.getElementById('product-title').textContent = 'Dettagli Prodotto';

        const data = updatedData || originalValues;

        document.getElementById('product-name').textContent = data.nome;
        document.getElementById('product-expiry').textContent = data.scadenza.includes('-') ? formatDateForDisplay(data.scadenza) : data.scadenza;
        document.getElementById('product-quantity').textContent = data.quantita;
        document.getElementById('product-unity').textContent = data.unita;

        saveButton.replaceWith(editButton);
        cancelButton.replaceWith(deleteButton);
    }

    saveButton.addEventListener('click', function () {
        const productId = document.getElementById('product-id').textContent.trim();
        const newName = document.getElementById('edit-name').value || originalValues.nome;
        const newExpiry = document.getElementById('edit-expiry').value || formatDateForInput(originalValues.scadenza);
        const newQuantity = document.getElementById('edit-quantity').value || originalValues.quantita;
        const newUnity = document.getElementById('edit-unity').value || originalValues.unita;

        fetch('/product_details', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                id_prodotto: productId,
                nome_prodotto: newName,
                data_scadenza: newExpiry,
                quantita: newQuantity,
                unita: newUnity
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    exitEditMode({
                        nome: newName,
                        scadenza: newExpiry,
                        quantita: newQuantity,
                        unita: newUnity
                    });

                    const updateEvent = new CustomEvent('productUpdated', { detail: data.product}); 
                    document.dispatchEvent(updateEvent); 
                } else {
                    alert('Errore: ' + data.message);
                }
            })
            .catch(error => console.error('Errore:', error));
    });

    //elimina

    function showDeleteConfirmation() {
        isDeleting = true;
        const confirmationDiv = document.getElementById('delete-confirmation');
        confirmationDiv.classList.remove('d-none');

        editButton.replaceWith(confirmDeleteButton);
        deleteButton.replaceWith(cancelDeleteButton);
    }

    function hideDeleteConfirmation() {
        isDeleting = false;
        const confirmationDiv = document.getElementById('delete-confirmation');
        confirmationDiv.classList.add('d-none');

        confirmDeleteButton.replaceWith(editButton);
        cancelDeleteButton.replaceWith(deleteButton);
    }

    editButton.addEventListener('click', function () {
        if (!isEditing) {
            enterEditMode();
        }
    });

    cancelButton.addEventListener('click', function () {
        if (isEditing) {
            exitEditMode();
        }
    });

    deleteButton.addEventListener('click', function () {
        showDeleteConfirmation();
    });

    confirmDeleteButton.addEventListener('click', function () {
        const productId = document.getElementById('product-id').textContent.trim();
        const data = {
            product: {
                id: productId,
            }
        };
        const deleteEvent = new CustomEvent('productDeleted', { detail: data.product});
        document.dispatchEvent(deleteEvent);

        fetch('/product_details', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ id_prodotto: productId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Nascondi i dettagli del prodotto
                    document.querySelector('.product-details').classList.add('d-none');
                    document.querySelector('.alert-warning').classList.remove('d-none');                    
                    hideDeleteConfirmation();
                } else {
                    alert('Errore: ' + data.message);
                }
            })
            .catch(error => console.error('Errore:', error));
    });

    cancelDeleteButton.addEventListener('click', function () {
        hideDeleteConfirmation();
    });

});