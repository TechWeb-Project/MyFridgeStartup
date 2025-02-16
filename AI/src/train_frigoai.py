import pandas as pd
import ollama
import os


def check_file(dataset_path):
    if not os.path.exists(dataset_path):
        print(f"Il file '{dataset_path}' non e' stato trovato.")
        exit(1)


def load_dataset(dataset_path):
    # load dataset and check that contain the required columns
    print("Caricamento dataset ...")
    data = pd.read_csv(dataset_path)

    required_columns = ["title", "ingredients", "directions", "NER"]
    if not all(col in data.columns for col in required_columns):
        print(f"Il dataset deve contenere le seguenti colonne: {required_columns}")
        exit(1)

    return data


def preproocess_data(data):
    # prepare data for fine-tuning
    print("Preparazione del dataset per il fine-tuning ...")
    training_data = []

    for _, row in data.iterrows():
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
    # save data in a JSON file
    output_data = pd.DataFrame(training_data)
    output_data.to_json(output_path, orient="records", lines=True)
    print(f"Dataset pre-processato salvato in '{output_path}'.")


def train_model(training_data):
    print("Inizio del fine-tuning del modello FrigoAI ...")

    for entry in training_data:
        try:
            response = ollama.generate(model="FrigoAI", prompt=entry["prompt"])
            print(f"Prompt:\n{entry['prompt']}")
            print(f"Risposta Generata:\n{response['response']}")
        except Exception as e:
            print(f"Errore durante il fine-tuning per il prompt: {entry['prompt']}\n{e}")
    
    print("Fine=tuning completato.")


def main():
    dataset_path = "./data/Clean_ReceipeNLG.csv"
    output_path = "./data/training_data.json"

    check_file(dataset_path)

    data = load_dataset(dataset_path)
    training_data = preproocess_data(data)

    save_training_data(training_data, output_path)

    train_model(training_data)


if __name__ == "__main__":
    main()