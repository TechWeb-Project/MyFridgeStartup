<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FrigoAI - Crea la tua ricetta!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-dark text-white">
    <div class="container mt-5">
        <h1 class="text-center">🥑 FrigoAI - Crea la tua ricetta! 🥦</h1>

        <form id="recipe-form">
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

            let response = await fetch('/generate-recipe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    ingredients,
                    time
                })
            });

            let result = await response.json();
            document.getElementById('recipeResult').innerHTML = `
                <h3>🍽 Ricetta Generata:</h3>
                <p>${result.recipe}</p>
            `;
        }
    </script>
</body>

</html>