<?php

namespace App\Http\Controllers;
use App\Models\Categoria;
use App\Models\Prodotto;
use App\Models\Durata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Constants\UnitaMisura;
use App\Http\Controllers\AggiuntaController;

class AggiuntaController extends Controller
{

    public function create() 
    {
        $categorie = Categoria::all();
        $durate = Durata::all();
        return view('add', compact('categorie', 'durate'));
    }
    

    public function store(Request $request)
    {
        try {
            // Validazione della richiesta
            $validated = $request->validate([
                'nome_prodotto' => 'required|string|max:255',
                'categoria_id'  => 'required|exists:categorie,id_categoria',
                'durata_id'    => 'required|exists:durata,id_durata',
                'quantita'     => 'required|integer|min:1',
                'unita'        => 'required|string'
            ]);

            // Recupero della categoria e durata con controllo
            $categoria = Categoria::find($validated['categoria_id']);
            if (!$categoria) {
                throw new \Exception('Categoria non trovata');
            }

            $durata = Durata::find($validated['durata_id']);
            if (!$durata) {
                throw new \Exception('Durata non trovata');
            }

            // Aggiunta alla tabella di pivot
            $categoriaDurataId = DB::table('categoria_durata')->insertGetId([
                'id_categoria'    => $validated['categoria_id'],
                'id_durata'      => $validated['durata_id'],
                'durata_standard' => $categoria->giorni_categoria,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // Calcolo della data di scadenza
            $giorni_categoria = $categoria->giorni_categoria;
            $moltiplicatore_durata = $durata->moltiplicatore_durata;
            $data_scadenza = now()->addDays($giorni_categoria * $moltiplicatore_durata);

            // Creazione del nuovo prodotto
            $prodotto = Prodotto::create([
                'nome_prodotto'       => $validated['nome_prodotto'],
                'data_scadenza'       => $data_scadenza,
                'quantita'            => $validated['quantita'],
                'unita_misura'        => $validated['unita'],
                'id_categoria_durata' => $categoriaDurataId
            ]);

            return response()->json([
                'success' => true,
                'product' => $prodotto,
                'message' => 'Prodotto aggiunto con successo'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'aggiunta del prodotto: ' . $e->getMessage()
            ], 500);
        }
    }
    
}
