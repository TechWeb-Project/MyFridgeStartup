from flask import Flask, request, jsonify
from flask_cors import CORS
import ollama
import logging

app = Flask(__name__)
CORS(app)

@app.route('/generate-recipe', methods=['POST'])
def generate_recipe():
    data = request.get_json()
    ingredients = data.get('ingredients', '')
    time = data.get('time', 60)
    num_people = data.get('num_people', 1)  
    rejected = data.get('rejected', False)

    app.logger.info('Ricevuto richiesta per generare ricetta', extra={'ingredients': ingredients, 'time': time, 'num_people': num_people, 'rejected': rejected})

    if not ingredients:
        app.logger.error('Nessun ingrediente fornito')
        return jsonify({'error': 'Nessun ingrediente fornito'}), 400

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
        app.logger.info('Ricetta generata con successo', extra={'response': response})
        return jsonify({'recipe': response["response"]})
    except Exception as e:
        app.logger.error('Errore durante la generazione della ricetta', extra={'error': str(e)})
        return jsonify({'error': str(e)}), 500


def main():
    logging.basicConfig(level=logging.INFO)
    app.run(host='0.0.0.0', port=5000, debug=True)


if __name__ == '__main__':
    main()