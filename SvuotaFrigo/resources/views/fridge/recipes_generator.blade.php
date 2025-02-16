<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recipes Generator</title>

    <link rel="stylesheet" href="{{ asset('css/recipes_generator.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
</head>

<body>
    <div class="recipes-generator-page">
        <div class="bg-light p-4 border rounded-lg m-2 flex-grow-1">
            <div class="container mt-5">
                <h1 class="text-center">🥑 Crea la tua ricetta! 🥦</h1>

                <form id="recipe-form">
                    @csrf
                    <div class="mb-3">
                        <label for="ingredients" class="form-label">Ingredienti disponibili (separati da virgola):</label>
                        <input type="text" id="ingredients" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="time" class="form-label">Tempo massimo per la preparazione (minuti): <span id="timeValue">60</span></label>
                        <input type="range" id="time" class="form-range" min="5" max="120" value="60" oninput="updateTimeValue(this.value)">
                    </div>

                    <button type="button" class="btn btn-primary w-100" onclick="generateRecipe()">Genera Ricetta</button>
                </form>

                <div class="mt-4" id="recipeResult"></div>
            </div>

            <script>
                function updateTimeValue(value) {
                    document.getElementById('timeValue').innerText = value;
                }

                async function generateRecipe() {
                    let ingredients = document.getElementById('ingredients').value;
                    let time = document.getElementById('time').value;
                    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    console.log('Invio richiesta per generare ricetta', {
                        ingredients,
                        time
                    });

                    let response = await fetch('/generate-recipe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            ingredients,
                            time
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
                        `;
                    } else {
                        console.error('Errore nella risposta', response);
                        document.getElementById('recipeResult').innerHTML = `
                            <h3>❌ Errore:</h3>
                            <p>Si è verificato un errore durante la generazione della ricetta.</p>
                        `;
                    }
                }
            </script>
        </div>
    </div>
</body>

</html>