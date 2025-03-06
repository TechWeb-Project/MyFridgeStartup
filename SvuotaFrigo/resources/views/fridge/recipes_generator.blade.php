@push('styles')
<link rel="stylesheet" href="{{ asset('css/recipes_generator.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://unpkg.com/typewriter-effect@latest/dist/core.js"></script>
<script src="{{ asset('js/recipes_generator.js') }}" defer></script>
@endpush

<div class="recipes-generator-container">
    <div class="recipes-generator-page">
        <div class="bg-light p-4 border rounded-lg m-2 flex-grow-1">
            <div class="container mt-5">

                <div class="d-flex align-items-center justify-content-center mb-4">
                    <img src="{{ asset('images/ia.png') }}" alt="Immagine ricetta" class="img-fluid" style="max-width: 10000px; margin-top: -20px;">
                </div>

                <form id="recipe-form">
                    @csrf
                    <div id="recipesCounter" class="mb-3">
                        <span class="badge bg-info">
                            <i class="fas fa-sync-alt"></i>
                            Ricette disponibili oggi: <span id="availableRecipes">--</span>
                        </span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Alimenti selezionati dal frigo:</strong></label>
                        <div class="selected-ingredients p-2 border rounded position-relative">
                            <span id="selected_ingredients"></span>
                            <button type="button" id="clearFridgeIngredients" class="btn btn-sm btn-light rounded-circle position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%);">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <input type="hidden" id="fridge_ingredients" value="{{ $ingredients ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="external_ingredients" class="form-label">Ingredienti non presenti nel frigorifero (separati da virgola):</label>
                        <input type="text" id="external_ingredients" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label for="num_people">Numero di persone:</label>
                        <input type="number" id="num_people" class="form-control" min="1" value="1">
                    </div>

                    <div class="mb-3">
                        <label for="time" class="form-label">Tempo massimo per la preparazione (minuti): <span id="timeValue">60</span></label>
                        <input type="range" id="time" class="form-range" min="5" max="120" value="60" oninput="updateTimeValue(this.value)">
                    </div>

                    <button type="button" class="btn btn-primary w-100" onclick="generateRecipe()">Genera Ricetta</button>
                </form>

                <div class="recipe-result-container">
                    <div class="mt-4" id="recipeResult"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>