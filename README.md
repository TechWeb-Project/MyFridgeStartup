# wAIsteless: Il tuo assistente AI per la cucina

## 📋 Descrizione
wAIsteless è un'applicazione web innovativa che risolve il problema quotidiano di cosa cucinare con gli alimenti già disponibili nel frigorifero. Utilizzando un modello di intelligenza artificiale personalizzato, l'applicazione genera ricette su misura basate sugli ingredienti selezionati, aiutando a ridurre lo spreco alimentare e semplificando la pianificazione dei pasti.

## ✨ Funzionalità principali

### 🧊 Gestione del frigorifero virtuale
- Visualizzazione intuitiva degli alimenti con ripiani organizzati per categoria
- Aggiunta, modifica ed eliminazione dei prodotti
- Monitoraggio delle scadenze con indicatori visivi
- Tracciamento delle quantità rimanenti

### 🍳 Generatore di ricette AI
- Selezione rapida degli ingredienti disponibili
- Generazione di ricette personalizzate basate sugli ingredienti selezionati
- Parametrizzazione per tempo di preparazione e numero di persone
- Ricette complete con ingredienti, istruzioni e valori nutrizionali

### 📊 Dashboard statistiche
- **Per utenti premium**: Analisi del risparmio, riduzione dello spreco, pattern nutrizionali
- **Per amministratori**: Monitoraggio dell'utilizzo della piattaforma, prestazioni AI, comportamento degli utenti

## 🤖 Tecnologia AI
L'applicazione utilizza un modello di intelligenza artificiale basato su Llama3.2, implementato tramite Ollama:

- **Dataset**: RecipeNLG da Kaggle (oltre 2 milioni di ricette)
- **Preprocessing**: Script Python personalizzato per la pulizia e normalizzazione dei dati
- **Fine-tuning**: Processo in tre fasi con un sottoinsieme di 30.000 ricette selezionate
- **Deployment locale**: Funzionamento offline tramite Ollama, garantendo privacy e velocità

## 🛠️ Stack tecnologico
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: Laravel PHP Framework
- **AI**: Llama3.2 tramite Ollama
- **Database**: MySQL
- **Deployment**: Docker, Python Flask API per l'interfacciamento con Ollama

## 📝 Requisiti
- PHP >= 8.1
- Composer
- Node.js e NPM
- Docker (opzionale per il deployment)
- Python >= 3.8 (per il componente AI)
- Ollama con modello Llama3.2 installato

## ⚙️ Installazione

1. Clonare il repository:
```bash
git clone https://github.com/yourusername/wAIsteless.git
cd wAIsteless
```

2. Installare le dipendenze PHP:
```bash
composer install
```

3. Installare le dipendenze JavaScript:
```bash
npm install && npm run dev
```

4. Configurare l'ambiente:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configurare il database nel file .env e eseguire le migrazioni:
```bash
php artisan migrate --seed
```

6. Avviare il server Flask per l'AI:
```bash
cd ai-service
pip install -r requirements.txt
python app.py
```

7. Avviare l'applicazione Laravel:
```bash
php artisan serve
```

## 📊 Piani di abbonamento
- **Piano Base**: Gestione del frigorifero, generazione limitata di ricette (10 al giorno)
- **Piano Premium**: Generazione illimitata di ricette, dashboard statistiche personalizzate

## 🌟 Perché scegliere wAIsteless?
- **Riduzione dello spreco alimentare**: Utilizzo ottimizzato degli ingredienti disponibili
- **Risparmio di tempo**: Fine delle indecisioni su cosa cucinare
- **Scoperta di nuove ricette**: Proposte creative basate sugli ingredienti disponibili
- **Privacy garantita**: Elaborazione locale dei dati grazie ai modelli AI open source
- **Funzionamento offline**: Nessuna necessità di connessione Internet costante

## 🚧 Stato del progetto
Il progetto è attualmente in fase di sviluppo attivo. Per contribuire o segnalare problemi, aprire una issue o una pull request.

---

*wAIsteless - Intelligenza artificiale per combattere gli sprechi alimentari*
