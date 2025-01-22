from flask import Flask, request, jsonify
import ollama

app = Flask(__name__)

@app.route('/generate', methods=['POST'])
def generate_receipe():
    data = request.get_json()
    ingredients = data.get('ingredients', '')
    time = data.get('time', 60)

    if not ingredients:
        return jsonify({'error': 'Nessun ingrediente fornito'}), 400
    
    prompt = f"""
    Ingredienti disponibili: {ingredients}
    Tempo massimo: {time} minuti
    Genera una ricetta dettagliata:
    """

    try:
        response = ollama.generate(model="FrigoAI", prompt=prompt)
        return jsonify({'receipe': response["response"]})
    except Exception as e:
        return jsonify({'error': str(e)}), 500


def main():
    app.run(host='0.0.0.0', port=5000, debug=True)


if __name__ == '__main__':
    main()