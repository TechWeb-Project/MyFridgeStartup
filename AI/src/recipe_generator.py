import uvicorn
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
import ollama
import logging

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

class RecipeRequest(BaseModel):
    ingredients: str
    time: int = 60
    num_people: int = 1
    rejected: bool = False

@app.post("/generate-recipe")
async def generate_recipe(request: RecipeRequest):
    ingredients = request.ingredients
    time = request.time
    num_people = request.num_people
    rejected = request.rejected

    logging.info('Received request to generate recipe', extra={'ingredients': ingredients, 'time': time, 'num_people': num_people, 'rejected': rejected})

    if not ingredients:
        logging.error('No ingredients provided')
        raise HTTPException(status_code=400, detail='No ingredients provided')

    prompt = f"""
    Ingredienti disponibili: {ingredients}
    Tempo massimo: {time} minuti
    Numero di persone: {num_people}
    Genera una ricetta dettagliata:
    """

    if rejected:
        prompt += "\nNota: La ricetta precedente è stata rifiutata, per favore genera una ricetta diversa."

    try:
        response = ollama.generate(model="FrigoAI", prompt=prompt)
        logging.info('Recipe generated successfully', extra={'response': response})
        return {'recipe': response["response"]}
    except Exception as e:
        logging.error('Error during recipe generation', extra={'error': str(e)})
        raise HTTPException(status_code=500, detail=str(e))

def main():
    logging.basicConfig(level=logging.INFO)
    uvicorn.run(app, host='0.0.0.0', port=5000)

if __name__ == '__main__':
    main()