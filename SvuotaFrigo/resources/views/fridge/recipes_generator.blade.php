<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recipes Generator</title>

    <link rel="stylesheet" href="{{ asset('css/recipes_generator.css') }}">
    
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="{{ asset('js/recipes_generator.js') }}" defer></script>
</head>

<body>
    <div class="recipes-generator-page">
        <div class="bg-light p-4 border rounded-lg m-2 flex-grow-1">
            <div class="container mt-5">
                <h1 class="text-center">🥑 Crea la tua ricetta! 🥦</h1>

                <form id="recipe-form">
                    @csrf
                    <div class="mb-3">
                        <label for="fridge_ingredients" class="form-label">Ingredienti disponibili nel frigorifero (separati da virgola):</label>
                        <input type="text" id="fridge_ingredients" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="external_ingredients" class="form-label">Ingredienti non presenti nel frigorifero (separati da virgola):</label>
                        <input type="text" id="external_ingredients" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="time" class="form-label">Tempo massimo per la preparazione (minuti): <span id="timeValue">60</span></label>
                        <input type="range" id="time" class="form-range" min="5" max="120" value="60" oninput="updateTimeValue(this.value)">
                    </div>

                    <button type="button" class="btn btn-primary w-100" onclick="generateRecipe()">Genera Ricetta</button>
                </form>

                <div class="mt-4" id="recipeResult"></div>
            </div>
        </div>
    </div>
</body>

</html>