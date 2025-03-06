async function updateRecipesCounter() {
    try {
        console.log('Updating recipes counter...');
        const response = await fetch('/get-remaining-recipes');
        const data = await response.json();
        console.log('Counter response:', data);
        
        const counterElement = document.getElementById('availableRecipes');
        console.log('Counter element exists:', !!document.getElementById('availableRecipes'));
        if (counterElement) {
            if (data.isPremium) {
                counterElement.textContent = '∞';
            } else {
                counterElement.textContent = Math.max(0, data.remaining);
            }
            console.log('Counter updated to:', counterElement.textContent);
        } else {
            console.error('Counter element not found');
        }
    } catch (error) {
        console.error('Error updating counter:', error);
    }
}

function showPremiumPopup() {
    const popup = `
        <div class="modal fade" id="premiumPopup" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">✨ Passa a Premium!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Hai raggiunto il limite giornaliero di ricette generate.</p>
                        <p>Passa a Premium per generare ricette illimitate e accedere a funzionalità esclusive!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non ora</button>
                        <a href="/premium" class="btn btn-warning">Scopri Premium</a>
                    </div>
                </div>
            </div>
        </div>`;
    
    document.body.insertAdjacentHTML('beforeend', popup);
    const modal = new bootstrap.Modal(document.getElementById('premiumPopup'));
    modal.show();
}

// Modifica la funzione updateIngredientsDisplay
function updateIngredientsDisplay() {
    const fridgeIngredients = document.getElementById('fridge_ingredients').value;
    const selectedIngredientsSpan = document.getElementById('selected_ingredients');
    
    if (fridgeIngredients) {
        const ingredientsList = fridgeIngredients.split(',')
            .map(ingredient => ingredient.trim())
            .filter(ingredient => ingredient) // Rimuove elementi vuoti
            .map(ingredient => `<span class="me-1 mb-1">${ingredient}</span>`)
            .join(', ');
        
        selectedIngredientsSpan.innerHTML = ingredientsList;
    } else {
        selectedIngredientsSpan.innerHTML = '';
    }
}

// Modifica la funzione showDeletePopup
function showDeletePopup(x, y) {
    // Verifica se ci sono ingredienti da eliminare
    const fridgeIngredients = document.getElementById('fridge_ingredients').value;
    if (!fridgeIngredients.trim()) {
        return; // Non mostrare il popup se non ci sono ingredienti
    }

    // Rimuovi eventuali popup esistenti
    const existingPopup = document.querySelector('.delete-popup');
    if (existingPopup) existingPopup.remove();

    const popup = document.createElement('div');
    popup.className = 'delete-popup';
    popup.innerHTML = `
        <div class="popup-content">
            <p>Vuoi eliminare tutti gli ingredienti?</p>
            <div class="popup-buttons">
                <button class="btn btn-sm btn-secondary cancel-btn">Annulla</button>
                <button class="btn btn-sm btn-danger confirm-btn">Elimina</button>
            </div>
        </div>
        <div class="popup-arrow"></div>
    `;

    // Posiziona il popup - aggiustato per puntare meglio alla X
    popup.style.left = `${x - 100}px`; // Centrato rispetto alla X
    popup.style.top = `${y - 80}px`; // Spostato più in alto
    document.body.appendChild(popup);

    // Event listeners
    const confirmBtn = popup.querySelector('.confirm-btn');
    const cancelBtn = popup.querySelector('.cancel-btn');
    
    confirmBtn.addEventListener('click', () => {
        document.getElementById('fridge_ingredients').value = '';
        updateIngredientsDisplay();
        popup.remove();
    });

    cancelBtn.addEventListener('click', () => popup.remove());

    // Chiudi il popup se si clicca fuori
    document.addEventListener('click', function closePopup(e) {
        if (!popup.contains(e.target) && !e.target.closest('#clearFridgeIngredients')) {
            popup.remove();
            document.removeEventListener('click', closePopup);
        }
    });
}

// Event listeners
document.addEventListener("DOMContentLoaded", () => {
    // Inizializza la visualizzazione degli ingredienti
    updateIngredientsDisplay();
    
    document.getElementById('clearFridgeIngredients').addEventListener('click', (e) => {
        const button = e.target.closest('#clearFridgeIngredients');
        const rect = button.getBoundingClientRect();
        showDeletePopup(rect.right - 30, rect.top + window.scrollY); 
        e.stopPropagation();
    });

    updateRecipesCounter();
});

function updateTimeValue(value) {
    document.getElementById('timeValue').innerText = value;
}

// Aggiungi questa funzione per tracciare la generazione di ricette lato client
async function trackRecipeGeneration() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    try {
        const response = await fetch('/track-recipe-generation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            }
        });
        
        return await response.json();
    } catch (error) {
        console.error('Failed to track recipe generation:', error);
        return { success: false, error: error.message };
    }
}

// Modifica la funzione generateRecipe
async function generateRecipe(rejected = false) {
    const recipeResult = document.getElementById('recipeResult');
    
    try {
        // Verifica se l'utente può generare una nuova ricetta
        const limitResponse = await fetch('/get-remaining-recipes');
        const limitData = await limitResponse.json();
        
        if (!limitData.isPremium && limitData.remaining <= 0) {
            showPremiumPopup();
            throw new Error('Limite giornaliero raggiunto');
        }

        let fridge_ingredients = document.getElementById('fridge_ingredients').value;
        let external_ingredients = document.getElementById('external_ingredients').value;
        let ingredients = fridge_ingredients + (external_ingredients ? ', ' + external_ingredients : '');
        let time = document.getElementById('time').value;
        let num_people = document.getElementById('num_people').value;
        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        console.log('Invio richiesta per generare ricetta', {
            ingredients,
            time,
            num_people, 
            rejected
        });

        // Mostra l'elemento recipeResult e visualizza il loader
        recipeResult.style.display = 'block';
        recipeResult.innerHTML = `
            <div class="text-center">
                <div id="loadingEmoji">🍳</div>
                <p class="mt-2">Generazione ricetta in corso, non ricaricare la pagina...</p>
            </div>
        `;
        document.getElementById('loadingEmoji').style.display = 'block';

        // Chiamata all'API Python
        const response = await fetch('http://127.0.0.1:5000/generate-recipe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json' 
            },
            body: JSON.stringify({
                ingredients,
                time,
                num_people, 
                rejected
            })
        });

        console.log('Response received:', response);
        const data = await response.json();
        console.log('Response data:', data);

        if (!response.ok) {
            throw new Error(data.error || 'Errore durante la generazione della ricetta');
        }

        // Traccia la generazione ricetta dopo il successo
        await trackRecipeGeneration();
        
        // Aggiorna il contatore ricette
        await updateRecipesCounter();

        let md_recipe = marked.parse(data.recipe);
        console.log('Parsed markdown:', md_recipe);

        // Codifica i parametri e rimuovi eventuali apici
        const encodedRecipe = encodeURIComponent(data.recipe).replace(/'/g, "\\'");
        const encodedIngredients = encodeURIComponent(ingredients).replace(/'/g, "\\'");
        
        // Usa data attributes invece di parametri inline
        recipeResult.innerHTML = `
            <h3>🍽 Ricetta Generata:</h3>
            <div id="recipe-content"></div>
            <div class="button-group" style="display: none; flex-direction: row; gap: 10px;">
                <button class="btn btn-success mt-3" 
                        id="acceptButton" 
                        data-ingredients="${encodedIngredients}" 
                        data-time="${time}" 
                        data-recipe="${encodedRecipe}" 
                        data-num-people="${num_people}">Accetta</button>
                <button class="btn btn-danger mt-3" onclick="generateRecipe(true)">Rifiuta</button>
            </div>
        `;

        // Aggiungi event listener dopo aver creato il bottone
        document.getElementById('acceptButton').addEventListener('click', function() {
            const button = this;
            acceptRecipe(
                button.dataset.ingredients,
                button.dataset.time,
                button.dataset.recipe,
                button.dataset.numPeople
            );
        });

        // Usa la libreria Typewriter per l'effetto di digitazione
        const typewriter = new Typewriter('#recipe-content', {
            delay: 10,
            cursor: '▌'
        });

        typewriter
            .typeString(md_recipe)
            .callFunction(() => {
                const buttonGroup = document.querySelector('.button-group');
                if (buttonGroup) buttonGroup.style.display = 'flex';
                updateRecipesCounter();
            })
            .start();

    } catch (error) {
        console.error('Detailed error:', {
            message: error.message,
            stack: error.stack
        });
        
        recipeResult.innerHTML = `
            <div class="alert alert-danger">
                <h3>❌ Errore:</h3>
                <p>Si è verificato un errore durante la generazione della ricetta.</p>
                <p>Dettagli: ${error.message}</p>
                <button class="btn btn-primary mt-3" onclick="generateRecipe()">Riprova</button>
            </div>
        `;
        
        // Salva l'errore
        await saveError('Errore API', error.message);
    }
}

async function acceptRecipe(ingredients, time, recipe, num_people) {
    try {
        const decodedRecipe = decodeURIComponent(recipe);
        // Prima salva la ricetta
        const saveResponse = await saveRecipe(decodedRecipe, ingredients, time, num_people);
        
        if (saveResponse.success) {
            // Estrai gli ingredienti con le quantità dal markdown della ricetta
            const ingredientsWithQuantities = extractIngredientsWithQuantities(decodedRecipe);

            console.log('Updating quantities for ingredients:', ingredientsWithQuantities);

            // Aggiorna le quantità nel frigo
            const updateResponse = await updateFridgeQuantities(ingredientsWithQuantities);
            
            if (updateResponse.success) {
                console.log('Quantities updated successfully:', updateResponse);
                showSuccessMessage('Ricetta salvata e quantità aggiornate con successo!');
                
                // Aggiungi il bottone per generare una nuova ricetta
                const recipeResult = document.getElementById('recipeResult');
                const newRecipeButton = document.createElement('div');
                newRecipeButton.className = 'text-center mt-3';
                newRecipeButton.innerHTML = `
                    <button class="btn btn-primary" onclick="generateNewRecipe()">
                        Genera una nuova ricetta
                    </button>
                `;
                recipeResult.appendChild(newRecipeButton);
                
                // Nascondi i bottoni Accetta/Rifiuta
                const buttonGroup = document.querySelector('.button-group');
                if (buttonGroup) {
                    buttonGroup.style.display = 'none';
                }
            } else {
                console.error('Failed to update quantities:', updateResponse);
                showErrorMessage('Ricetta salvata ma errore nell\'aggiornamento delle quantità');
            }
        } else {
            showErrorMessage('Errore durante il salvataggio della ricetta');
        }
    } catch (error) {
        console.error('Error in acceptRecipe:', error);
        showErrorMessage('Errore durante il processo: ' + error.message);
    }
}

function extractIngredientsWithQuantities(markdown) {
    console.log('Markdown ricevuto:', markdown);
    
    const lines = markdown.split('\n');
    const ingredients = [];
    let inIngredientsList = false;

    const ingredientHeaders = [
        '**Ingredienti:**',
        '**Lista degli ingredienti:**',
        'Ingredienti:',
        'Lista degli ingredienti:',
        '## Ingredienti',
        '## Lista degli ingredienti',
        '### Ingredienti',
        '### Lista degli ingredienti'
    ];

    for (const line of lines) {
        console.log('Analisi linea:', line);
        
        // Controlla se la linea contiene uno degli header degli ingredienti
        if (ingredientHeaders.some(header => line.trim().includes(header.trim()))) {
            console.log('Trovata sezione ingredienti:', line);
            inIngredientsList = true;
            continue;
        }

        if (inIngredientsList) {
            // Esci dalla sezione ingredienti se troviamo un nuovo header o una linea vuota
            if (line.includes('<h2>') || line.startsWith('##') || line.trim() === '') {
                if (ingredients.length > 0) {
                    break;
                }
                continue; 
            }
            
            // Rimuovi i tag HTML, markdown e bullet points
            const cleanLine = line
                .replace(/<[^>]*>/g, '')     // Rimuove tag HTML
                .replace(/^[-*•]\s*/, '')    // Rimuove bullet points
                .replace(/\*\*/g, '')        // Rimuove bold markdown
                .replace(/\*/g, '')          // Rimuove italic markdown
                .trim();

            if (cleanLine) {
                console.log('Elaborazione linea ingrediente:', cleanLine);
                const ingredient = parseIngredientLine(cleanLine);
                if (ingredient) {
                    console.log('Ingrediente estratto:', ingredient);
                    ingredients.push(ingredient);
                }
            }
        }
    }

    console.log('Totale ingredienti estratti:', ingredients.length);
    
    if (ingredients.length === 0) {
        console.error('Markdown completo ricevuto:', markdown);
        throw new Error('Nessun ingrediente trovato nella ricetta. Verifica che la sezione "Ingredienti" sia presente e formattata correttamente.');
    }
    
    return ingredients;
}

function parseIngredientLine(line) {
    const cleanLine = line.replace(/\([^)]*\)/g, '').trim();
    
    const regex = /^(\d+(?:[.,]\d+)?)\s*(g|kg|ml|l|cucchiai|cucchiaio|cucchiaini|cucchiaino|pizzico|q\.b\.|grammi|millilitri|litri|unità)?\s*(?:di\s+)?(.+?)$/i;
    
    console.log('Parsing clean line:', cleanLine);
    
    const match = cleanLine.match(regex);
    
    if (match) {
        let [_, quantity, unit, name] = match;
        
        // Converte la quantità in numero
        quantity = parseFloat(quantity.replace(',', '.'));
        
        // Standardizza l'unità di misura
        unit = unit ? unit.toLowerCase() : 'unità';
        switch(unit) {
            case 'grammi': unit = 'g'; break;
            case 'millilitri': unit = 'ml'; break;
            case 'litri': unit = 'l'; break;
            case 'cucchiaio': unit = 'cucchiai'; break;
            case 'cucchiaino': unit = 'cucchiaini'; break;
            case 'kg': break;
            case 'q.b.': unit = 'q.b.'; break;
            default: unit = 'unità';
        }
        
        // Pulisci il nome dell'ingrediente
        name = name.toLowerCase()
                  .replace(/^[- ]+/, '')  
                  .replace(/[.,]$/, '')   
                  .trim();
        
        if (name && quantity > 0) {
            const result = {
                name: name,
                quantity: quantity,
                unit: unit
            };
            console.log('Parsed ingredient:', result);
            return result;
        }
    }

    console.log('No valid match found for line:', line);
    return null;
}

async function updateFridgeQuantities(ingredientsWithQuantities) {
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    try {
        // Log per debug
        console.log('Sending ingredients to update:', ingredientsWithQuantities);

        const response = await fetch('/update-fridge-quantities', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                ingredients: ingredientsWithQuantities
            })
        });

        const data = await response.json();
        console.log('Server response:', data);
        
        if (!response.ok) {
            throw new Error(data.message || `Errore del server (${response.status})`);
        }

        // Dispatch events only if update was successful
        if (data.success && data.updatedProducts) {
            data.updatedProducts.forEach(product => {
                console.log('Dispatching update for product:', product);
                const updateEvent = new CustomEvent('productUpdated', {
                    detail: product
                });
                document.dispatchEvent(updateEvent);

                if (product.quantita <= 0) {
                    console.log('Dispatching delete for product:', product);
                    const deleteEvent = new CustomEvent('productDeleted', {
                        detail: { id: product.id_prodotto }
                    });
                    document.dispatchEvent(deleteEvent);
                }
            });
        }

        return data;
    } catch (error) {
        console.error('Errore durante l\'aggiornamento delle quantità:', error);
        throw error;
    }
}

function generateNewRecipe() {
    // Resetta i campi input
    document.getElementById('fridge_ingredients').value = '';
    document.getElementById('external_ingredients').value = '';
    document.getElementById('num_people').value = 1;
    document.getElementById('time').value = 30; // Resetta anche il tempo se necessario
    
    // Resetta il contenuto della ricetta
    const recipeResult = document.getElementById('recipeResult');
    recipeResult.innerHTML = '';
    recipeResult.style.display = 'none';
    
    // Aggiorna la visualizzazione degli ingredienti
    updateIngredientsDisplay();
    
    // Rimuovi eventuali alert ancora presenti
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => alert.remove());
    
    // Reimposta lo scroll in cima alla pagina
    window.scrollTo(0, 0);
}

async function saveError(type, message) {
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    await fetch('/save-error', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            type: type,
            message: message
        })
    });
}

async function saveRecipe(recipeData, ingredients, time, num_people) {
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    try {
        // Estrai gli ingredienti con le quantità dal markdown
        const ingredientsWithQuantities = extractIngredientsWithQuantities(recipeData);
        
        const response = await fetch('/save-recipe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                recipe: {
                    name: recipeData.split('\n')[0].replace('#', '').trim(), // Prende il titolo della ricetta
                    instructions: recipeData
                },
                ingredientsWithQuantities,
                time,
                num_people
            })
        });

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Errore durante il salvataggio della ricetta');
        }

        return data;
    } catch (error) {
        console.error('Error saving recipe:', error);
        throw error;
    }
}

function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Inserisci l'alert prima del div recipeResult
    const recipeResult = document.getElementById('recipeResult');
    recipeResult.parentNode.insertBefore(alertDiv, recipeResult);

    // Rimuovi l'alert dopo 5 secondi
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function showErrorMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Inserisci l'alert prima del div recipeResult
    const recipeResult = document.getElementById('recipeResult');
    recipeResult.parentNode.insertBefore(alertDiv, recipeResult);

    // Rimuovi l'alert dopo 5 secondi
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("recipes_generator");
    const toggleButton = document.getElementById("toggle_sidebar");
    const realFridge = document.getElementById("details_div"); // Real Fridge div
    const productDetails = document.getElementById("products_div"); 
    const overlay = document.getElementById("overlay");

    toggleButton.addEventListener("click", function () {
        sidebar.classList.toggle("open"); // Mostra/nasconde la sidebar
        toggleButton.classList.toggle("shift-left"); 
        realFridge.classList.toggle("shift-left"); // Sposta il Real Fridge
        productDetails.classList.toggle("shift-right");
        overlay.classList.toggle("visible");
    });

    overlay.addEventListener("click", function () {
        sidebar.classList.remove("open");
        toggleButton.classList.remove("shift-left");
        realFridge.classList.remove("shift-left");
        productDetails.classList.remove("shift-right");
        overlay.classList.remove("visible"); // Nasconde overlay
    });
});



