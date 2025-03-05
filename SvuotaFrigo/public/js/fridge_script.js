document.addEventListener("DOMContentLoaded", () => {
    let selectionMode = false; // Inizia disattivato
    let selectedProducts = new Map(); // Mappa per prodotti selezionati
    let isEditing = false;
    let isDeleting = false;
    let WasCreated = false;
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
            if(WasCreated)
            {
                //proviamo a metter qui l'immagine
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
            console.log('ID:', id);

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
            .catch(error => console.error('Avviso :', error));

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
        //se sei in modalità eliminazione, nascondi il messaggio di conferma
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
            // Per ciascun prodotto selezionato, recupera l'elemento della card
            // e leggi il nome aggiornato direttamente dal DOM.
            const updatedNames = [];
            selectedProducts.forEach((product, id) => {
                const card = document.querySelector(`.product-card[data-id='${id}']`);
                if (card) {
                    // Assumiamo che il nome aggiornato sia visualizzato nel front della card
                    const nameElem = card.querySelector(".product-front .product-name");
                    if (nameElem) {
                        updatedNames.push(nameElem.textContent.trim());
                    } else {
                        // Fallback se non troviamo l'elemento (raramente)
                        updatedNames.push(product.nome);
                    }
                }
            });
    
            const selectedIngredients = updatedNames.join(', ');
    
            // Aggiorna il campo nascosto con i nomi aggiornati
            const fridgeIngredientsInput = document.getElementById('fridge_ingredients');
            fridgeIngredientsInput.value = selectedIngredients;
    
            // Aggiorna la visualizzazione dei badge degli ingredienti
            const selectedIngredientsSpan = document.getElementById('selected_ingredients');
            if (selectedIngredientsSpan) {
                const ingredientsList = updatedNames
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
        const productDetails = document.querySelector('.product-details');
        productDetails.classList.remove('d-none');

        // Get the flip container and trigger the flip animation
        const contentContainer = document.querySelector('.content-container');
        const frontText = document.querySelector('.front-text');
        const backText = document.querySelector('.back-text');
        
        // Flip the container if it's not already flipped
        if (!contentContainer.classList.contains('flipped')) {
            contentContainer.classList.add('flipped');
            frontText.style.display = 'none';
            backText.style.display = '';   
        }


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

            // Aggiorna il nome nella parte retro
            const backNameElem = productCard.querySelector(".product-back .product-name");
            if (backNameElem) {
                backNameElem.textContent = updatedProduct.nome; 
                backNameElem.classList.add('animate-update');
            }            
    
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
                // Format unit display based on the unit type
                let formattedUnit = updatedProduct.unita;
                switch(updatedProduct.unita.toLowerCase()) {
                    case 'grammi':
                        formattedUnit = 'gr';
                        break;
                    case 'ml':
                        formattedUnit = 'ml';
                        break;
                    case 'fette':
                        formattedUnit = 'ftt';
                        break;
                    case 'pezzi':
                        formattedUnit = 'pz';
                        break;
                }
                
                quantityUnit.textContent = formattedUnit;
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
            
            // Get all cards after the deleted one in the current shelf
            const currentContainer = currentShelf.querySelector('.products-container');
            const cardsInCurrentShelf = Array.from(currentContainer.children);
            const deletedIndex = cardsInCurrentShelf.indexOf(productCard);
            
            // Step 1: Delete animation for the target card
            productCard.classList.add('animate-delete');
            
            // Step 2: Move subsequent cards in current shelf to the left
            const cardsToMoveInShelf = cardsInCurrentShelf.slice(deletedIndex + 1);
            cardsToMoveInShelf.forEach(card => {
                card.classList.add('animate-slide-left');
            });
            
            // Get cards from next shelves that need to move up
            for (let i = currentShelfIndex + 1; i < shelves.length; i++) {
                const shelf = shelves[i];
                const container = shelf.querySelector('.products-container');
                const firstCard = container.firstElementChild;
                const remainingCards = Array.from(container.children).slice(1);
                
                if (firstCard) {
                    // Add both animations to firstCard
                    firstCard.classList.add('animate-shelf-change');
                    // Make remaining cards slide left simultaneously
                    remainingCards.forEach(card => {
                        card.classList.add('animate-slide-left');
                    });
                }
            }

            // Remove animations and reposition cards after completion
            setTimeout(() => {
                productCard.remove();
                
                document.querySelectorAll('.animate-slide-left').forEach(card => {
                    card.classList.remove('animate-slide-left');
                });
                
                document.querySelectorAll('.animate-shelf-change').forEach(card => {
                    card.classList.remove('animate-shelf-change');
                    const currentShelf = card.closest('.shelf');
                    const shelfIndex = shelves.indexOf(currentShelf);
                    if (shelfIndex > 0) {
                        const previousShelf = shelves[shelfIndex - 1];
                        const previousContainer = previousShelf.querySelector('.products-container');
                        previousContainer.appendChild(card);
                    }
                });
            }, 2600);
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
                
                // Mostra la vista placeholder predefinita
                const placeholderView = document.querySelector('.alert-warning.product-details-format');
                placeholderView.classList.remove('d-none');
                placeholderView.style.display = 'block';

                // Reset titolo
                document.getElementById('product-title').textContent = 'Dettagli Prodotto';
                
                // Nascondi conferma eliminazione e ripristina bottoni
                hideDeleteConfirmation();
                
                // Reset immagine se presente
                const productImage = document.querySelector('.product-image');
                if (productImage) {
                    productImage.style.display = 'none';
                    document.querySelector('.product-image-container').style.display = 'none';
                }
            } else {
                alert('Errore: ' + data.message);
            }
        })
        .catch(error => console.error('Errore:', error));
    });

    cancelDeleteButton.addEventListener('click', function () {
        hideDeleteConfirmation();
    });

    //flip visualizza
    flip.addEventListener('click', function () {
        const contentContainer = document.querySelector('.content-container');
        const frontText = document.querySelector('.front-text');
        const backText = document.querySelector('.back-text');
        
        // Toggle the flipped class on the container
        contentContainer.classList.toggle('flipped');
        
        // Toggle button text
        if (frontText.style.display === 'none') {
            frontText.style.display = '';
            backText.style.display = 'none';
        } else {
            frontText.style.display = 'none';
            backText.style.display = '';
        }
    });

    // Update the addButton event listener
    addButton.addEventListener('click', function (e) {
        e.preventDefault();

        const formData = {
            nome_prodotto: document.getElementById('nome_prodotto').value,
            categoria_id: document.getElementById('categoria_id').value,
            durata_id: document.getElementById('durata_id').value,
            unita: document.getElementById('unita').value,
            quantita: document.getElementById('quantita').value
        };
        console.log('Dati del form:', formData);

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/add_product', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reset del form
                document.getElementById('addProductForm').reset();
                
                // Unisci formData con l'id_prodotto restituito dal controller
                const productData = { ...formData, id_prodotto: data.id };
                
                // Dispatch dell'evento con i dati aggiornati
                const addEvent = new CustomEvent('productAdded', { detail: productData });
                document.dispatchEvent(addEvent);
            }

        })
        .catch(error => {
            console.error('Errore:', error);
        });
    });

    // Add the productAdded event listener
    document.addEventListener('productAdded', function(e) {
        const newProduct = e.detail;
        const shelves = document.querySelectorAll('.shelf');
        let targetShelf = null;
        
        // Find first shelf with space
        for (const shelf of shelves) {
            if (shelf.querySelector('.products-container').children.length < 4) {
                targetShelf = shelf;
                break;
            }
        }

        // Create new shelf if needed
        if (!targetShelf) {
            targetShelf = createNewShelf();
        }


        console.log('newProduct:', newProduct);


        // Create and add new product card with animation
        const newCard = createProductCard(newProduct);
        newCard.classList.add('animate-add');
        targetShelf.querySelector('.products-container').appendChild(newCard);

        // Remove animation class after completion
        setTimeout(() => {
            newCard.classList.remove('animate-add');
        }, 500);
    });

    // Helper function to create a new shelf
    function createNewShelf() {
        const fridgeContainer = document.querySelector('.door');
        
        // Create main shelf container
        const newShelf = document.createElement('div');
        newShelf.className = 'shelf';
        
        // Create shelf wrapper (white background container)
        const shelfWrapper = document.createElement('div');
        shelfWrapper.className = 'shelf-wrapper';
        
        // Create products container
        const productsContainer = document.createElement('div');
        productsContainer.className = 'products-container';
        
        // Assemble the structure
        shelfWrapper.appendChild(productsContainer);
        newShelf.appendChild(shelfWrapper);
        fridgeContainer.appendChild(newShelf);
        
        return newShelf;
    }
    
    // Aggiungi questa mappa all'inizio del file, fuori dall'event listener DOMContentLoaded
    const categorieImmagini = {
        '1': 'dairy-products.png',
        '2': 'meat.png',
        '3': 'fish.png',
        '4': 'fruit.png',
        '5': 'vegetable.png',
        '6': 'wheat-sack.png',
        '7': 'bread-loafs.png',
        '8': 'bakery.png',
        '9': 'soft-drink.png',
        '10': 'canned-food.png',
        '11': 'sauces.png',
        '12': 'edamame.png',
        '13': 'soy-meat.png',
        '14': 'roll-cake.png',
        '15': 'ice-cream-sandwich.png'
    };

    // Funzione per ottenere l'immagine in base alla categoria
    function getImmagineForCategoria(nomeCategoria) {
        return categorieImmagini[nomeCategoria] || 'default.png';
    }

    // Modifica la funzione createProductCard
    function createProductCard(product) {
        const card = document.createElement('div');
        card.className = 'product-card';
        WasCreated = true;

        // Set data attributes
        card.dataset.id = product.id_prodotto;
        card.dataset.nome = product.nome_prodotto;
        card.dataset.quantita = product.quantita;
        card.dataset.unita = product.unita;
        card.dataset.scadenza = product.data_scadenza;

    
        // Format unit display based on the unit type
        let formattedUnit = product.unita;
        switch(product.unita?.toLowerCase()) { // Added optional chaining
            case 'grammi':
                formattedUnit = 'gr';
                break;
            case 'ml':
                formattedUnit = 'ml';
                break;
            case 'fette':
                formattedUnit = 'ftt';
                break;
            case 'pezzi':
                formattedUnit = 'pz';
                break;
        }
    
        // Rest of the code remains the same
        const expiryDate = new Date(product.data_scadenza);
        const today = new Date();
        const daysUntilExpiry = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));
    
        let dotClass = 'dot-green';
        if (daysUntilExpiry <= 0) {
            dotClass = 'dot-red';
        } else if (daysUntilExpiry <= 2) {
            dotClass = 'dot-orange';
        }
    
        const immagine = getImmagineForCategoria(product.categoria_id);
    
        card.innerHTML = `
            <div class="product-front">
                <div class="product-img">
                    <img src="${window.location.origin}/images/icone_frigo/${immagine}" 
                         alt="${product.nome_prodotto}"
                         onerror="this.src='${window.location.origin}/images/icone_frigo/default.png'">
                    <span class="expiration-dot ${dotClass}"></span>
                </div>
    
                <div class="product-name text-truncate fw-bold fs-6 d-block">
                    ${product.nome_prodotto}
                </div>
                
                <div class="quantity-badge">
                    <span class="quantity-number">${product.quantita}</span>
                    <span class="quantity-unit">${formattedUnit}</span>
                </div>
            </div>
    
            <div class="product-back">
                <span class="product-name">${product.nome_prodotto}</span>
                <div class="checkbox">✔</div>
            </div>
        `;
    
        return card;
    }
    
    // Aggiungi listener per l'aggiornamento dei prodotti
    document.addEventListener('productUpdated', function(e) {
        const updatedProduct = e.detail;
        const productCard = document.querySelector(`[data-id="${updatedProduct.id_prodotto}"]`);
        
        if (productCard) {
            // Aggiorna la quantità
            const quantityNumber = productCard.querySelector('.quantity-number');
            const quantityUnit = productCard.querySelector('.quantity-unit');
            
            if (quantityNumber) {
                quantityNumber.textContent = updatedProduct.quantita;
                quantityNumber.classList.add('animate-update');
            }
            
            if (quantityUnit) {
                quantityUnit.textContent = updatedProduct.unita_misura;
            }

            // Rimuovi l'animazione dopo un po'
            setTimeout(() => {
                quantityNumber.classList.remove('animate-update');
            }, 500);
        }
    });

    // Aggiungi listener per la rimozione dei prodotti
    document.addEventListener('productDeleted', function(e) {
        const productId = e.detail.id;
        const productCard = document.querySelector(`[data-id="${productId}"]`);
        
        if (productCard) {
            // Aggiungi animazione di uscita
            productCard.classList.add('fade-out');
            
            // Rimuovi l'elemento dopo l'animazione
            setTimeout(() => {
                productCard.remove();
            }, 300);
        }
    });
});