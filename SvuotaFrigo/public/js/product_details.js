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

            // Quando clicco su "Salva", aggiorna solo nome e data di scadenza
            
            saveButton.addEventListener('click', function() {
            const idProdotto = document.getElementById('edit-id').value;
            const newName = document.getElementById('edit-name').value;
            const newExpiry = document.getElementById('edit-expiry').value;

            fetch(`/fridge_dashboard`, {
                method: 'PUT',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                    // Aggiorna la vista
                    document.getElementById('product-name').innerText = newName;
                    document.getElementById('product-expiry').innerText = newExpiry;

                    // Nasconde il form di modifica
                    editForm.classList.add('d-none');
                } else {
                    alert("Errore nell'aggiornamento del prodotto.");
                }
            })
            .catch(error => console.error('Errore:', error));
        });


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