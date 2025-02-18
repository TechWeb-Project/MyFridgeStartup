from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import ollama
import logging

app = FastAPI()

class RecipeRequest(BaseModel):
    ingredients: str
    time: int = 60

@app.post("/generate-recipe")
async def generate_receipe(request: RecipeRequest):
    ingredients = request.ingredients
    time = request.time

    logging.info('Ricevuto richiesta per generare ricetta', extra={'ingredients': ingredients, 'time': time})

    if not ingredients:
        logging.error('Nessun ingrediente fornito')
        raise HTTPException(status_code=400, detail='Nessun ingrediente fornito')
    
    prompt = f"""
    Ingredienti disponibili: {ingredients}
    Tempo massimo: {time} minuti
    Genera una ricetta dettagliata:
    """

    try:
        response = ollama.generate(model="FrigoAI", prompt=prompt)
        logging.info('Ricetta generata con successo', extra={'response': response})
        return {'recipe': response["response"]}
    except Exception as e:
        logging.error('Errore durante la generazione della ricetta', extra={'error': str(e)})
        raise HTTPException(status_code=500, detail=str(e))

if __name__ == '__main__':
    import uvicorn
    logging.basicConfig(level=logging.INFO)
    uvicorn.run(app, host='0.0.0.0', port=5000, log_level="info")