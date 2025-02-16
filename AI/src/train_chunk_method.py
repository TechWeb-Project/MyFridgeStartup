import pandas as pd
import ollama
import os


def check_file(dataset_path):
    if not os.path.exists(dataset_path):
        print(f"Il file '{dataset_path}' non e' stato trovato.")
        exit(1)


def process_chunk(chunk):
    print("Preparazione del blocco per il fine-tuning")
    training_data = []

    for _, row in chunk.iterrows():
        title       = row["title"]
        ingredients = row["ingredients"]
        directions  = row["directions"]
        ner         = row["NER"]

        prompt = f"""
        Ingredienti disponibili: {ner}
        """
        response = f"""
        - Nome della ricetta: {title}
        - Lista completa degli ingredienti con grammature: {ingredients}
        - Procedura passo-passo: {directions}
        """
        
        training_data.append({"prompt": prompt.strip(), "response": response.strip()})

    return training_data


def save_training_data(training_data, output_path):
    output_data = pd.DataFrame(training_data)
    if os.path.exists(output_path):
        output_data.to_json(output_path, orient="records", lines=True, mode='a')
    else:
        output_data.to_json(output_path, orient="records", lines=True)
    print(f"Blocco salvato in '{output_path}'.")


def train_model(training_data):
    print("Inizio del fine-tuning del modello FrigoAI ...")

    for entry in training_data:
        try:
            response = ollama.generate(model="FrigoAI", prompt=entry["prompt"])
            print(f"Prompt:\n{entry['prompt']}")
            print(f"Risposta Generata:\n{response['response']}")
        except Exception as e:
            print(f"Errore durante il fine-tuning per il prompt: {entry['prompt']}\n{e}")
    
    print("Fine-tuning completato per il blocco corrente.")


def main():
    dataset_path = "./data/Clean_ReceipeNLG.csv"
    output_path = "./data/training_data.json"
    chunk_size = 1000  

    check_file(dataset_path)

    for chunk in pd.read_csv(dataset_path, chunksize=chunk_size):
        training_data = process_chunk(chunk)
        save_training_data(training_data, output_path)
        train_model(training_data)


if __name__ == "__main__":
    main()