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


    // Conferma eliminazione e aggiorna il DB
    confirmDeleteBtn.addEventListener('click', function() {
        fetch(`/fridge_dashboard`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                hideAllForms(); // Nasconde tutto
                productCard.classList.add('d-none'); // Nasconde il prodotto
                deleteMessage.classList.remove('d-none'); // Mostra messaggio di eliminazione
            } else {
                alert("Errore nell'eliminazione del prodotto.");
            }
        })
        .catch(error => console.error('Errore:', error));
    });
});