import pandas as pd
import os


def check_dataset(dataset_path):
    if not os.path.exists(dataset_path):
        raise FileNotFoundError(f"Il file '{dataset_path}' non esiste.")
    print(f"File '{dataset_path}' trovato.")


def check_columns(dataset_path, required_columns):
    df = pd.read_csv(dataset_path)
    missing_columns = [col for col in required_columns if col not in df.columns]

    if missing_columns:
        raise ValueError(f"Mancano le seguenti colonne richieste: {missing_columns}")
    print("Tutte le colonne necessarie sono presenti.")
    
    return df


def clean_csv_data(df):
    print("Inizio della pulizia dei dati...")

    # fill null values
    df['title'] = df['title'].fillna('unknown').str.strip()
    df['ingredients'] = df['ingredients'].fillna('unknown').str.strip()
    df['directions'] = df['directions'].fillna('unknown').str.strip()
    df['link'] = df['link'].fillna('unknown').str.strip()
    df['source'] = df['source'].fillna('unknown').str.strip()
    df['NER'] = df['NER'].fillna('unknown').str.strip()
    
    # normalize data
    df['title'] = df['title'].str.title()  
    df['ingredients'] = df['ingredients'].str.replace(r'\s+', ' ', regex=True)  
    df['directions'] = df['directions'].str.replace(r'\s+', ' ', regex=True)  
    df['NER'] = df['NER'].str.lower()

    columns_to_keep = ['title', 'ingredients', 'directions', 'source', 'NER']

    return df[columns_to_keep]


def save_clean_dataset(df, output_path):
    df.to_csv(output_path, index=False)
    print(f"Dataset pulito salvato in '{output_path}'.")


def main():
    dataset_path = "./data/RecipeNLG_dataset.csv"
    output_path  = "./data/Clean_ReceipeNLG.csv"

    required_columns = ["title", "ingredients", "directions", 'link', 'source', "NER"]

    try:
        check_dataset(dataset_path)
        df = check_columns(dataset_path, required_columns)

        df_cleaned = clean_csv_data(df)
        save_clean_dataset(df_cleaned, output_path)
    except (FileNotFoundError, ValueError) as e:
        print(f"Errore {e}")


if __name__ == "__main__":
    main()