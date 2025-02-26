document.addEventListener("DOMContentLoaded", () => {
    // Mostra gli ingredienti selezionati
    const fridgeIngredients = document.getElementById('fridge_ingredients').value;
    const selectedIngredientsSpan = document.getElementById('selected_ingredients');
    
    if (fridgeIngredients) {
        // Converti la stringa di ingredienti in una lista
        const ingredientsList = fridgeIngredients.split(',')
            .map(ingredient => ingredient.trim())
            .map(ingredient => `<span class="badge bg-primary me-1">${ingredient}</span>`)
            .join(' ');
        
        selectedIngredientsSpan.innerHTML = ingredientsList;
    }
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

        document.getElementById('recipeResult').innerHTML = '<div id="loadingEmoji">🍳</div>';
        document.getElementById('loadingEmoji').style.display = 'block';

        const response = await fetch('http://127.0.0.1:5000/generate-recipe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                ingredients,
                time,
                num_people, 
                rejected
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Errore durante la generazione della ricetta');
        }

        let md_recipe = marked.parse(data.recipe);
        
        recipeResult.innerHTML = `
            <h3>🍽 Ricetta Generata:</h3>
            <div id="recipe-content"></div>
            <div class="button-group" style="display: none; flex-direction: row; gap: 10px;">
                <button class="btn btn-success mt-3" onclick="acceptRecipe('${ingredients}', '${time}', \`${md_recipe}\`, '${num_people}')">Accetta</button>
                <button class="btn btn-danger mt-3" onclick="generateRecipe(true)">Rifiuta</button>
            </div>
        `;

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

    } catch (error) {
        console.error('Errore:', error);
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

    let response = await fetch('/save-recipe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            ingredients: ingredients,
            time: time,
            recipe: recipe,
            num_people: num_people
        })
    });

    if (response.ok) {
        document.querySelector('.button-group').style.display = 'none';
        document.getElementById('recipeResult').innerHTML += `
            <h3>✅ Ricetta salvata con successo!</h3>
            <button class="btn btn-primary mt-3" onclick="generateNewRecipe()">Genera una nuova ricetta</button>
        `;
    } else {
        document.getElementById('recipeResult').innerHTML += `
            <p>❌ Errore durante il salvataggio della ricetta.</p>
        `;
    }
}

function generateNewRecipe() {
    document.getElementById('fridge_ingredients').value = '';
    document.getElementById('external_ingredients').value = '';
    document.getElementById('num_people').value = 1;
    document.getElementById('recipeResult').innerHTML = '';
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