async function updateRecipesCounter() {
    try {
        const response = await fetch('/get-remaining-recipes');
        const data = await response.json();
        
        const counterElement = document.getElementById('availableRecipes');
        if (data.isPremium) {
            counterElement.innerHTML = '∞ (Premium)';
            counterElement.parentElement.classList.remove('bg-info');
            counterElement.parentElement.classList.add('bg-warning');
        } else {
            counterElement.textContent = data.remaining;
        }
    } catch (error) {
        console.error('Error updating recipes counter:', error);
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

async function generateRecipe(rejected = false) {
    const recipeResult = document.getElementById('recipeResult');
    
    try {
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

        document.getElementById('recipeResult').innerHTML = `
            <div class="text-center">
                <div id="loadingEmoji">🍳</div>
                <p class="mt-2">Generazione ricetta in corso, non ricaricare la pagina...</p>
            </div>
        `;
        document.getElementById('loadingEmoji').style.display = 'block'

        // Aggiungi log per debug della risposta
        console.log('Sending request to FastAPI...');
        
        const response = await fetch('http://127.0.0.1:5000/generate-recipe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json' // Aggiungi Accept header
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
            if (response.status === 403 && data.error === 'limit_reached') {
                showPremiumPopup();
                throw new Error('Limite giornaliero raggiunto');
            }
            throw new Error(data.error || 'Errore durante la generazione della ricetta');
        }

        // Verifica che data.recipe esista
        if (!data.recipe) {
            throw new Error('Formato risposta API non valido: manca la ricetta');
        }

        // Assicurati che marked sia caricato
        if (typeof marked === 'undefined') {
            console.error('marked library not loaded');
            throw new Error('Errore nel rendering della ricetta: libreria marked non caricata');
        }

        let md_recipe = marked.parse(data.recipe);
        console.log('Parsed markdown:', md_recipe);

        // Rendi visibile il div del risultato
        recipeResult.style.display = 'block';

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

        const typewriter = new Typewriter('#recipe-content', {
            delay: 10,
            cursor: '▌'
        });

        typewriter
            .typeString(md_recipe)
            .callFunction(() => {
                const buttonGroup = document.querySelector('.button-group');
                if (buttonGroup) buttonGroup.style.display = 'flex';
            })
            .start();

        // Dopo typewriter.start()
        console.log('Recipe display completed');

        await updateRecipesCounter();

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
        saveError('Errore API', error.message);
    }
}

async function acceptRecipe(ingredients, time, recipe, num_people) {
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        // Decodifica i parametri
        recipe = decodeURIComponent(recipe);
        ingredients = decodeURIComponent(ingredients);

        console.log('Recipe markdown:', recipe);
        
        // Estrae gli ingredienti con le quantità
        const ingredientsWithQuantities = extractIngredientsWithQuantities(recipe);
        
        console.log('Extracted ingredients:', ingredientsWithQuantities);
        
        if (!ingredientsWithQuantities || ingredientsWithQuantities.length === 0) {
            throw new Error('Nessun ingrediente trovato nella ricetta');
        }

        // Estrai il nome della ricetta dal markdown
        const titleMatch = recipe.match(/\*\*(.*?)\*\*/);
        const recipeName = titleMatch ? titleMatch[1].trim() : 'Ricetta senza nome';

        // Prima salva la ricetta
        const saveResponse = await fetch('/save-recipe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                ingredients: ingredients.split(',').map(i => i.trim()),
                ingredientsWithQuantities: ingredientsWithQuantities,
                time: time,
                recipe: {
                    name: recipeName,
                    instructions: recipe
                },
                num_people: num_people
            })
        });

        const saveData = await saveResponse.json();
        if (!saveResponse.ok) {
            throw new Error(saveData.message || 'Errore durante il salvataggio della ricetta');
        }
        
        await updateFridgeQuantities(ingredientsWithQuantities);
        
        document.querySelector('.button-group').style.display = 'none';
        document.getElementById('recipeResult').innerHTML += `
            <div class="alert alert-success mt-3">
                <h3>✅ Ricetta salvata con successo!</h3>
                <p>Le quantità degli ingredienti sono state aggiornate nel tuo frigo.</p>
                <button class="btn btn-primary mt-3" onclick="generateNewRecipe()">Genera una nuova ricetta</button>
            </div>
        `;
    } catch (error) {
        console.error('Errore durante il salvataggio:', error);
        document.getElementById('recipeResult').innerHTML += `
            <div class="alert alert-danger mt-3">
                <p>❌ Errore: ${error.message}</p>
                <button class="btn btn-primary mt-2" onclick="location.reload()">Ricarica la pagina</button>
            </div>
        `;
    }
}

function extractIngredientsWithQuantities(markdown) {
    console.log('Markdown ricevuto:', markdown);
    
    const lines = markdown.split('\n');
    const ingredients = [];
    let inIngredientsList = false;

    const ingredientHeaders = [
        '**Ingredienti:**',
        '**Lista degli ingredienti:**'
    ];

    for (const line of lines) {
        console.log('Analisi linea:', line);
        
        // Controlla se la linea contiene uno degli header degli ingredienti
        if (ingredientHeaders.includes(line.trim())) {
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
        // Validate ingredients before sending
        if (!Array.isArray(ingredientsWithQuantities) || ingredientsWithQuantities.length === 0) {
            throw new Error('Lista ingredienti non valida');
        }

        // Validate each ingredient
        ingredientsWithQuantities.forEach((ingredient, index) => {
            if (!ingredient.name || typeof ingredient.quantity !== 'number' || !ingredient.unit) {
                console.error('Ingrediente non valido:', ingredient);
                throw new Error(`Ingrediente #${index + 1} non valido: richiesti name, quantity e unit`);
            }
        });

        console.log('Invio richiesta di aggiornamento quantità:', {
            ingredients: ingredientsWithQuantities,
            endpoint: '/update-fridge-quantities'
        });
        
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
        
        console.log('Risposta ricevuta:', {
            status: response.status,
            data: data
        });

        if (!response.ok) {
            console.error('Errore dal server:', data);
            throw new Error(data.message || `Errore del server (${response.status})`);
        }

        return data;
    } catch (error) {
        console.error('Dettaglio errore:', {
            message: error.message,
            stack: error.stack,
            response: error.response
        });
        
        if (error instanceof TypeError && error.message === 'Failed to fetch') {
            throw new Error('Impossibile raggiungere il server. Verifica la tua connessione.');
        }
        
        throw new Error(`Errore durante l'aggiornamento delle quantità: ${error.message}`);
    }
}

function generateNewRecipe() {
    document.getElementById('fridge_ingredients').value = '';
    document.getElementById('external_ingredients').value = '';
    document.getElementById('num_people').value = 1;
    document.getElementById('recipeResult').innerHTML = '';
    updateIngredientsDisplay(); 
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


document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("recipes_generator");
    const toggleButton = document.getElementById("toggle_sidebar");
    const realFridge = document.getElementById("details_div"); // Real Fridge div

    toggleButton.addEventListener("click", function () {
        sidebar.classList.toggle("open"); // Mostra/nasconde la sidebar
        realFridge.classList.toggle("shift-left"); // Sposta il Real Fridge
    });
});



