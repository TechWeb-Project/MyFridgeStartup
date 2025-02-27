document.addEventListener('DOMContentLoaded', function() {
    const editButton = document.getElementById('edit-btn');
    const deleteButton = document.getElementById('deleteProductBtn');
    const saveButton = document.createElement('button');
    const cancelButton = document.createElement('button');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    saveButton.id = 'save-btn';
    saveButton.className = 'btn custom-btn';
    saveButton.textContent = 'Salva';

    cancelButton.id = 'cancel-btn';
    cancelButton.className = 'btn btn-secondary';
    cancelButton.textContent = 'Annulla';

    let isEditing = false;
    let originalValues = {};

    function formatDateForInput(dateStr) {
        const [day, month, year] = dateStr.split('/');
        return `${year}-${month}-${day}`;
    }

    function formatDateForDisplay(dateStr) {
        const [year, month, day] = dateStr.split('-');
        return `${day}/${month}/${year}`;
    }

    function enterEditMode() {
        isEditing = true;
        document.getElementById('product-title').textContent = 'Modifica Prodotto';

        originalValues = {
            nome: document.getElementById('product-name').textContent.trim(),
            scadenza: document.getElementById('product-expiry').textContent.trim(),
            quantita: document.getElementById('product-quantity').textContent.trim(),
            unita: document.getElementById('product-unity').textContent.trim()
        };

        document.getElementById('product-name').innerHTML = `<input type="text" id="edit-name" class="form-control" value="${originalValues.nome}">`;
        document.getElementById('product-expiry').innerHTML = `<input type="date" id="edit-expiry" class="form-control" value="${formatDateForInput(originalValues.scadenza)}" readonly>`;
        document.getElementById('product-quantity').innerHTML = `<input type="number" id="edit-quantity" class="form-control" value="${originalValues.quantita}">`;
        document.getElementById('product-unity').innerHTML = `<input type="text" id="edit-unity" class="form-control" value="${originalValues.unita}">`;

        // Permetti la modifica della data solo quando viene cliccato
        document.getElementById('edit-expiry').addEventListener('click', function() {
            this.removeAttribute('readonly');
        });

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

    editButton.addEventListener('click', function() {
        if (!isEditing) {
            enterEditMode();
        }
    });

    cancelButton.addEventListener('click', function() {
        if (isEditing) {
            exitEditMode();
        }
    });

    saveButton.addEventListener('click', function() {
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
            } else {
                alert('Errore: ' + data.message);
            }
        })
        .catch(error => console.error('Errore:', error));
    });

    // Gestione del bottone di eliminazione
    deleteButton.addEventListener('click', function() {
        const productId = document.getElementById('product-id').textContent.trim();
        if (confirm('Sei sicuro di voler eliminare questo prodotto?')) {
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
                    alert('Prodotto eliminato con successo.');
                    // Nascondi i dettagli del prodotto
                    document.querySelector('.product-details').classList.add('d-none');
                    document.querySelector('.alert-warning').classList.remove('d-none');
                } else {
                    alert('Errore: ' + data.message);
                }
            })
            .catch(error => console.error('Errore:', error));
        }
    });
});