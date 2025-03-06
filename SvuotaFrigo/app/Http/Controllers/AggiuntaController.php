<?php

namespace App\Http\Controllers;
use App\Models\Categoria;
use App\Models\Prodotto;
use App\Models\Durata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Constants\UnitaMisura;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Log;

class AggiuntaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create() 
    {
        $categorie = Categoria::all();
        $durate = Durata::all();
        return view('add', compact('categorie', 'durate'));
    }
    

    public function store(Request $request)
    {
        try {
            Log::info('Iniziando la creazione del prodotto', $request->all());

            // Validazione della richiesta
            $validated = $request->validate([
                'nome_prodotto' => 'required|string|max:255',
                'categoria_id'  => 'required|exists:categorie,id_categoria',
                'durata_id'     => 'required|exists:durata,id_durata',
                'quantita'      => 'required|integer|min:1',
                'unita'         => 'required|string'  
            ]);

            Log::info('Validazione passata', $validated);

            // Recupero della categoria e durata con controllo
            $categoria = Categoria::find($validated['categoria_id']);
            Log::info('Categoria trovata', ['categoria' => $categoria]);
            
            if (!$categoria) {
                throw new \Exception('Categoria non trovata');
            }

            $durata = Durata::find($validated['durata_id']);
            Log::info('Durata trovata', ['durata' => $durata]);
            
            if (!$durata) {
                throw new \Exception('Durata non trovata');
            }

            try {
                $categoriaDurataId = DB::table('categoria_durata')->insertGetId([
                    'id_categoria'    => $validated['categoria_id'],
                    'id_durata'      => $validated['durata_id'],
                    'durata_standard' => $categoria->giorni_categoria,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
                Log::info('Inserimento categoria_durata completato', ['id' => $categoriaDurataId]);
            } catch (\Exception $e) {
                Log::error('Errore nell\'inserimento categoria_durata', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            $giorni_categoria = $categoria->giorni_categoria;
            $moltiplicatore_durata = $durata->moltiplicatore_durata;
            $data_scadenza = now()->addDays($giorni_categoria * $moltiplicatore_durata);

            // Creazione del nuovo prodotto
            try {
                $prodotto = Prodotto::create([
                    'nome_prodotto'       => $validated['nome_prodotto'], 
                    'data_scadenza'       => $data_scadenza, 
                    'quantita'            => $validated['quantita'], 
                    'unita'               => $validated['unita'],  
                    'id_categoria_durata' => $categoriaDurataId, 
                    'created_at'          => now(),
                    'updated_at'          => now()
                ]);
                Log::info('Prodotto creato', ['prodotto' => $prodotto]);
            } catch (\Exception $e) {
                Log::error('Errore nella creazione del prodotto', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            // Inserimento nella tabella frigo
            try {
                DB::table('frigo')->insert([
                    'id_prodotto' => $prodotto->id_prodotto,
                    'id_user' => Auth::id()
                ]);
                Log::info('Prodotto collegato all\'utente');
            } catch (\Exception $e) {
                Log::error('Errore nel collegamento prodotto-utente', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            return response()->json([
                'success' => true,
                'product' => $prodotto,
                'id' => $prodotto->id_prodotto,
                'message' => 'Prodotto aggiunto con successo'
            ]);

        } catch (\Exception $e) {
            Log::error('Errore generale', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'aggiunta del prodotto: ' . $e->getMessage()
            ], 500);
        }
    }
    
}
