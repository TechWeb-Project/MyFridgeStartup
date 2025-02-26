document.addEventListener('DOMContentLoaded', function() {
    const editButton = document.getElementById('edit-btn');
    const editForm = document.getElementById('edit-form');
    const cancelEditBtn = document.getElementById('cancel-btn');
    const saveButton = document.getElementById('save-btn');

    const deleteButton = document.getElementById('deleteProductBtn'); 
    const deleteDiv = document.getElementById('deleteConfirmation'); 
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn'); 
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn'); 
    const deleteMessage = document.getElementById('deleteMessage'); 
    const productCard = document.getElementById('product-card');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Funzione per chiudere entrambi i div
    function hideAllForms() {
        editForm.classList.add('d-none');
        deleteDiv.classList.add('d-none');
    }

    // Cliccando su "Modifica", chiude eliminazione e mostra modifica
    editButton.addEventListener('click', function() {
        hideAllForms();
        editForm.classList.toggle('d-none');
    });

    // Cliccando su "Annulla" nel form di modifica, lo chiude
    cancelEditBtn.addEventListener('click', function() {
        editForm.classList.add('d-none');
    });

    // Cliccando su "Elimina", chiude modifica e mostra eliminazione
    deleteButton.addEventListener('click', function() {
        hideAllForms();
        deleteDiv.classList.toggle('d-none');
    });

    // Cliccando su "Annulla" nel div di eliminazione, lo chiude
    cancelDeleteBtn.addEventListener('click', function() {
        deleteDiv.classList.add('d-none');
        selectedProductId = null; // Resetta l'ID
    });
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    document.getElementById("edit-btn").addEventListener("click", function() {
        ////////////////////////////////////////////////////////////////////////////ORA FUNZIONA
        const productNameElement = document.getElementById("product-name");
        let productName = productNameElement.value;  // Se è un input
        if (!productName) {
            // Se non è un input, usa textContent
            productName = productNameElement.textContent.trim();
        }
        console.log("Product Name:", productName); // Verifica il valore ottenuto

        // Imposta il valore in "edit-product-id"
        const editProductIdElem = document.getElementById("edit-product-id");
        if(editProductIdElem) {
            editProductIdElem.textContent = document.getElementById("product-id").textContent.trim();;
        } else {
            console.error("L'elemento con id 'edit-product-id' non esiste.");
        }
        
        // Mostra il form di modifica
        document.getElementById("edit-form").classList.remove("d-none");
    });

    // Quando clicco su "Salva", aggiorna solo nome e data di scadenza
    saveButton.addEventListener('click', function() { 
        const editProductIdElem = document.getElementById("edit-product-id");
        let idProdotto = editProductIdElem ? editProductIdElem.textContent.trim() : '';
        const newName = document.getElementById('edit-name').value;
        const newExpiry = document.getElementById('edit-expiry').value;

        console.log("ID del prodotto in js: " + idProdotto);
        console.log("New name: " + newName);
        
        fetch('/product_details', {
            method: 'PUT',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                id_prodotto: idProdotto,
                nome_prodotto: newName,
                data_scadenza: newExpiry
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('product-name').textContent = data.product.nome;
                document.getElementById('product-expiry').textContent = data.product.data_scadenza;
            } else {
                alert("Errore: " + data.message);
            }
        })
        .catch(error => console.error('Errore:', error));
    });
                

////////////////////////////////////////////////////////////////////////////


    //ELIMINAZIONE:

    let selectedProductId = null;

    deleteButton.addEventListener('click', function () {
        selectedProductId = document.getElementById('product-id').textContent.trim(); // Ottiene l'ID del prodotto

        console.log("visualizzo id dopo il click di deleteButton:" ,selectedProductId); /// lo ricevo

        if (selectedProductId) {
            deleteDiv.classList.remove('d-none'); // Mostra la conferma di eliminazione
        } else {
            //controllo aggiuntivo ma non necessario
            alert("Errore: nessun prodotto selezionato.");
        }
    });

    // Quando confermo l'eliminazione, invia l'ID al controller tramite AJAX
    confirmDeleteBtn.addEventListener('click', function () {
        console.log("ID del prodotto da eliminare:", selectedProductId);
    
        if (!selectedProductId) {
            alert("Errore: nessun prodotto selezionato per l'eliminazione.");
            return;
        }
        
        fetch('/product_details', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ id_prodotto: selectedProductId }) // <-- Passa l'ID
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Prodotto eliminato con successo');
    
                // ✅ Nasconde il div di eliminazione
                deleteDiv.classList.add('d-none'); 
                
                // ✅ Mostra il messaggio di conferma eliminazione
                deleteMessage.classList.remove('d-none'); 
    
                // ✅ Ripristina il div ai valori iniziali senza nasconderlo
                resetProductDetails();
    
                // ✅ Dopo 2 secondi, nasconde il messaggio di eliminazione
                setTimeout(() => {
                    deleteMessage.classList.add('d-none');
                }, 2000);
    
            } else {
                console.error('Errore:', data.message);
            }
        })
        .catch(error => console.error('Errore nella richiesta:', error));
    });

    function resetProductDetails() {
        // ✅ Resetta tutti i campi del prodotto
        document.getElementById('product-id').textContent = "";
        document.getElementById('product-name').textContent = "";
        document.getElementById('product-expiry').textContent = "";
        document.querySelector('.product-category').textContent = "";
        document.querySelector('.product-image').src = "";
    
        // ✅ Nasconde i dettagli e mostra "Nessun prodotto selezionato"
        document.querySelector('.product-details').classList.add('d-none'); 
        document.querySelector('.alert-warning').classList.remove('d-none');
    
        // ✅ Resetta l'ID del prodotto
        document.getElementById('product-id-hidden').value = "";
        selectedProductId = null;
    }
    

});


/*bottoni che ci piacciono
https://uiverse.io/mi-series/wicked-sloth-7
https://uiverse.io/OliverZeros/bitter-parrot-97
https://uiverse.io/doniaskima/nervous-wombat-2
https://uiverse.io/KINGFRESS/purple-kangaroo-17
https://uiverse.io/SmookyDev/dull-wasp-37
https://uiverse.io/AlimurtuzaCodes/average-liger-0
https://uiverse.io/vinodjangid07/heavy-badger-29
https://uiverse.io/vinodjangid07/smart-emu-83
https://uiverse.io/OnCloud125252/angry-dragonfly-77
https://uiverse.io/vinodjangid07/curvy-lionfish-94
per selezione multipla https://uiverse.io/catraco/short-starfish-66
per modifica
https://uiverse.io/aaronross1/kind-bobcat-81
////
https://uiverse.io/himanshu9682/wise-shrimp-26
prefe di endi
il mio prefe
https://uiverse.io/barisdogansutcu/average-chipmunk-44
*/