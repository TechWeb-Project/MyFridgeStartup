function updateTimeValue(value) {
    document.getElementById('timeValue').innerText = value;
}

async function generateRecipe(rejected = false) {
    let fridge_ingredients = document.getElementById('fridge_ingredients').value;
    let external_ingredients = document.getElementById('external_ingredients').value;
    let ingredients = fridge_ingredients + (external_ingredients ? ', ' + external_ingredients : '');
    let time = document.getElementById('time').value;
    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    console.log('Invio richiesta per generare ricetta', {
        ingredients,
        time,
        rejected
    });

    document.getElementById('recipeResult').innerHTML = '<div id="loadingEmoji">🍳</div>';
    document.getElementById('loadingEmoji').style.display = 'block';

    try {
        let response = await fetch('/generate-recipe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                ingredients,
                time,
                rejected
            })
        });

        console.log('Risposta ricevuta', response);

        if (response.ok) {
            let result = await response.json();
            console.log('Risultato JSON', result);

            let md_recipe = marked.parse(result.recipe);

            document.getElementById('recipeResult').innerHTML = `
                <h3>🍽 Ricetta Generata:</h3>
                <div>${md_recipe}</div>
                <div class="button-group">
                    <button class="btn btn-success mt-3" onclick="acceptRecipe('${ingredients}', '${time}', \`${md_recipe}\`)">Accetta</button>
                    <button class="btn btn-danger mt-3" onclick="generateRecipe(true)">Rifiuta</button>
                </div>
            `;
        } else {
            let errorText = await response.text();
            console.error('Errore nella risposta', response);
            document.getElementById('recipeResult').innerHTML = `
                <h3>❌ Errore:</h3>
                <p>Si è verificato un errore durante la generazione della ricetta.</p>
                <p>Dettagli: ${errorText}</p>
            `;
            saveError('Errore nella risposta', errorText);
        }
    } catch (error) {
        console.error('Errore durante la richiesta', error);
        document.getElementById('recipeResult').innerHTML = `
            <h3>❌ Errore:</h3>
            <p>Si è verificato un errore durante la generazione della ricetta.</p>
            <p>Dettagli: ${error.message}</p>
        `;
        saveError('Errore durante la richiesta', error.message);
    } finally {
        document.getElementById('loadingEmoji').style.display = 'none';
    }
}

async function acceptRecipe(ingredients, time, recipe) {
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
            recipe: recipe
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
    document.getElementById('time').value = '';
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