# Dimostrazione del Progetto wAIsteless

## Introduzione (1,5 minuti)

Buongiorno a tutti. Oggi vi presentiamo "wAIsteless", un'applicazione web innovativa che risolve un problema comune: cosa cucinare con quello che abbiamo nel frigorifero.

wAIsteless offre una rappresentazione virtuale del vostro frigorifero, permettendovi di gestire gli alimenti e generare ricette personalizzate in base agli ingredienti disponibili, riducendo così lo spreco alimentare e semplificando la pianificazione dei pasti.

Nelle prossime dimostrazioni, vi mostreremo come:
- Aggiungere e gestire prodotti nel frigorifero virtuale
- Selezionare ingredienti e generare ricette personalizzate
- Accettare una ricetta e aggiornare automaticamente le quantità in frigo

## Gestione del Frigorifero (3 minuti)

Iniziamo con l'interfaccia principale del frigorifero virtuale. Come potete vedere, l'applicazione presenta una rappresentazione visiva di un frigorifero con diversi ripiani.

**Dimostrazione: Aggiunta di un prodotto**

Aggiungiamo alcuni prodotti al nostro frigorifero. Premiamo il pulsante "Aggiungi Prodotto" e compiliamo i campi:
- Nome: Pomodori ciliegino
- Categoria: Verdura
- Durata: Media conservazione
- Unità: grammi
- Quantità: 400

Dopo aver salvato, il prodotto appare automaticamente su un ripiano del frigorifero. L'applicazione organizza intelligentemente i prodotti in base alla categoria.

**Dimostrazione: Aggiunta di altri prodotti**

Aggiungiamo rapidamente altri ingredienti:
- Mozzarella (250g)
- Basilico (un mazzetto)
- Pasta integrale (500g)
- Uova (6 pezzi)
- Peperoni (300g)

**Dimostrazione: Visualizzazione dettagli e modifica**

Cliccando su un prodotto, ad esempio le uova, possiamo vedere i dettagli completi:
- Data di inserimento
- Quantità rimanente
- Data di scadenza

Da qui possiamo facilmente modificare le informazioni, ad esempio aggiornando la quantità da 6 a 4 uova, o eliminare completamente il prodotto se necessario.

## Generazione delle Ricette (3 minuti)

Ora passiamo alla funzionalità principale: la generazione di ricette basate sugli ingredienti disponibili.

**Dimostrazione: Selezione ingredienti**

Selezioneremo alcuni ingredienti per la nostra ricetta. Cliccando sui prodotti:
- Pomodori ciliegino
- Mozzarella
- Basilico

Notate come questi ingredienti vengono visualizzati come badge nella parte inferiore dello schermo. Una volta selezionati, clicchiamo sul pulsante "Inizia a Cucinare".

**Dimostrazione: Sidebar delle ricette**

Osservate come l'interfaccia si trasforma: la sidebar delle ricette si apre lateralmente, mentre il frigorifero virtuale rimane visibile ma si sposta a sinistra.

Nella sidebar possiamo specificare:
- Tempo di preparazione disponibile: impostiamo 30 minuti
- Numero di persone: 2 persone

Clicchiamo ora su "Genera Ricetta" e vediamo cosa ci propone l'applicazione...

**Dimostrazione: Visualizzazione della ricetta**

Ecco la ricetta generata: "Insalata Caprese con Pomodorini". La ricetta include:
- Ingredienti con quantità precise
- Tempo di preparazione
- Istruzioni passo-passo
- Valori nutrizionali stimati

La ricetta è calcolata automaticamente in base ai nostri ingredienti disponibili e ottimizzata per 2 persone.

## Accettazione Ricetta e Aggiornamento Frigorifero (2 minuti)

**Dimostrazione: Accettazione e salvataggio**

Se la ricetta ci soddisfa, possiamo accettarla cliccando su "Accetta Ricetta". Osserviamo cosa succede:
1. La ricetta viene salvata nella nostra collezione personale
2. Le quantità nel frigorifero vengono automaticamente aggiornate
3. Un messaggio di conferma appare brevemente

Verifichiamo ora il frigorifero: come potete vedere, le quantità di pomodori, mozzarella e basilico sono state diminuite proporzionalmente in base alla ricetta preparata.

**Dimostrazione: Generazione di una nuova ricetta**

Se la ricetta non ci avesse soddisfatto, avremmo potuto generare una nuova ricetta con gli stessi ingredienti cliccando su "Genera Altra Ricetta". Proviamo con ingredienti diversi:
- Uova
- Peperoni
- Pasta integrale

Generiamo una nuova ricetta... L'applicazione ci propone una "Pasta con Peperoni e Uova Strapazzate". Possiamo accettarla o generare alternative.

## Tecnologia IA e Ollama (3,5 minuti)

Dietro la magia delle ricette personalizzate di wAIsteless c'è un sofisticato modello di intelligenza artificiale basato su Llama3.2, implementato tramite Ollama.

**Dimostrazione: Architettura dell'IA**

Il cuore tecnologico della nostra applicazione è un modello linguistico avanzato che abbiamo personalizzato specificamente per la generazione di ricette. Ecco gli elementi chiave del nostro approccio:

**Modello di base: Llama3.2**
- Abbiamo scelto Llama3.2 come modello di base per la sua eccellente comprensione del linguaggio naturale
- È un modello open source, il che significa nessun costo di licenza e piena trasparenza sul funzionamento
- Offre prestazioni paragonabili ai modelli proprietari come ChatGPT, ma con maggiore flessibilità

**Dataset: RecipeNLG**
- Per l'addestramento, abbiamo utilizzato il dataset RecipeNLG proveniente da Kaggle
- Kaggle è una piattaforma che ospita competizioni di data science e dataset di alta qualità
- RecipeNLG contiene oltre 2 milioni di ricette con ingredienti, istruzioni e metadati
- Abbiamo creato una versione pulita chiamata Clean_RecipeNLG attraverso uno script Python personalizzato

**Processo di pulizia del dataset**
Il nostro script Python ha eseguito diverse operazioni di pulizia:
1. Rimozione dei duplicati e delle ricette incomplete
2. Standardizzazione delle unità di misura (grammi, tazze, cucchiai, ecc.)
3. Estrazione e normalizzazione dei tempi di preparazione
4. Categorizzazione degli ingredienti in gruppi alimentari
5. Calcolo automatico delle informazioni nutrizionali mancanti

**Implementazione tramite Ollama**
- Utilizziamo Ollama per il deployment e il fine-tuning del modello
- Ollama ci permette di eseguire il modello localmente, garantendo privacy e velocità di risposta
- Abbiamo ottimizzato i parametri di temperatura e top-k per ottenere ricette creative ma realistiche

**Processo di fine-tuning**
Abbiamo addestrato il modello attraverso un processo in tre fasi:
1. Acquisizione di un dataset di oltre 2 milioni ricette selezionate da Clean_RecipeNLG
2. Annotazione delle ricette con metadati nutrizionali e informazioni sugli ingredienti
3. Fine-tuning del modello con istruzioni specifiche per generare ricette basate su ingredienti disponibili

**Perché Ollama e non PyTorch/TensorFlow?**
- Ollama offre un'interfaccia semplificata che gestisce l'inferenza e l'interazione con il modello
- Non abbiamo dovuto implementare complesse pipeline di addestramento o gestire direttamente i tensori
- Il fine-tuning tramite prompt è più efficiente per il nostro caso d'uso rispetto alla rimodellazione completa dei pesi
- Per il nostro caso specifico, la manipolazione diretta dei pesi del modello non era necessaria

**Perché modelli open source?**
- Controllo completo sulle funzionalità e sul comportamento del modello
- Nessuna dipendenza da API esterne o servizi cloud che potrebbero cambiare pricing o disponibilità
- Personalizzazione su misura per le specifiche esigenze culinarie, come proporzioni precise degli ingredienti
- Funzionamento offline, cruciale per garantire il servizio anche in assenza di connessione

## Piano Premium e Dashboard Statistiche (3 minuti)

wAIsteless offre due livelli di account: base e premium. Vediamo le differenze e i vantaggi dell'account premium.

**Dimostrazione: Vantaggi dell'account Premium**

L'utente base ha accesso a:
- Gestione base del frigorifero virtuale
- Generazione limitata di ricette (10 al giorno)

L'utente premium, invece, beneficia di:
- Generazione illimitata di ricette
- Dashboard personalizzata con statistiche dettagliate

**Dimostrazione: Dashboard Statistiche Premium**

Accediamo ora alla dashboard dell'utente premium Marco. Ecco le statistiche personalizzate:
- Grafico del risparmio mensile grazie all'ottimizzazione degli ingredienti
- Riduzione dello spreco alimentare (qui vediamo una riduzione del 35% rispetto al mese precedente)
- Analisi nutrizionale dei pasti preparati nell'ultimo mese
- Ingredienti più utilizzati e suggerimenti per variare la dieta
- Proiezione della spesa alimentare futura basata sulle abitudini

**Dimostrazione: Dashboard Admin**

Come amministratori, abbiamo accesso a una dashboard completa divisa in due sezioni principali:

1. **Gestione Utenti**:
    - Tabella con tutti gli utenti registrati
    - Informazioni dettagliate: nome, cognome, email
    - Tipo di account (base, premium, amministratore)
    - Possibilità di modificare permessi e status degli account

2. **Statistiche Avanzate**:
    - **Monitoraggio errori**: log degli errori riscontrati dagli utenti, frequenza e tipologia
    - **Trend delle ricette**:
      * Ricette generate oggi 
      * Media giornaliera di generazione 
      * Tasso di accettazione delle ricette 
    - **Statistiche AI**:
      * Tempo medio di generazione 
      * Richieste completate con successo 
      * Utilizzo medio di CPU 
      * Utilizzo medio di memoria 

Questi dati sono fondamentali per comprendere l'efficacia della nostra piattaforma e guidare le future decisioni di sviluppo.

## Conclusione e Q&A (1 minuto)

In sintesi, wAIsteless offre:
- Un modo intuitivo per tenere traccia degli alimenti nel frigorifero
- Suggerimenti di ricette personalizzati in base agli ingredienti disponibili
- Aggiornamento automatico delle scorte dopo la preparazione dei piatti
- Riduzione dello spreco alimentare grazie all'utilizzo ottimale degli ingredienti
- Un piano premium con funzionalità avanzate e statistiche personalizzate
- Strumenti di analisi potenti per utenti e amministratori
- Tecnologia IA avanzata ma accessibile basata su modelli open source

La nostra applicazione risolve un problema quotidiano, aiutando gli utenti a:
- Risparmiare tempo nella pianificazione dei pasti
- Ridurre lo spreco alimentare
- Scoprire nuove ricette basate su ciò che hanno già a disposizione
- Gestire meglio la spesa alimentare
- Monitorare le proprie abitudini alimentari grazie alle statistiche

Grazie per l'attenzione. Siamo disponibili per domande o per esplorare ulteriormente qualsiasi funzionalità dell'applicazione.