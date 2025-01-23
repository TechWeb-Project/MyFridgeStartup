from flask import Flask, request, jsonify
import ollama
import logging

app = Flask(__name__)

@app.route('/generate', methods=['POST'])
def generate_receipe():
    data = request.get_json()
    ingredients = data.get('ingredients', '')
    time = data.get('time', 60)

    app.logger.info('Ricevuto richiesta per generare ricetta', extra={'ingredients': ingredients, 'time': time})

    if not ingredients:
        app.logger.error('Nessun ingrediente fornito')
        return jsonify({'error': 'Nessun ingrediente fornito'}), 400
    
    prompt = f"""
    Ingredienti disponibili: {ingredients}
    Tempo massimo: {time} minuti
    Genera una ricetta dettagliata:
    """

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