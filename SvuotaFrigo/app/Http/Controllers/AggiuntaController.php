<?php

namespace App\Http\Controllers;
use App\Models\Categoria;
use App\Models\Prodotto;
use App\Models\Durata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Constants\UnitaMisura;

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
        // Validazione della richiesta
        $validated = $request->validate([
            'nome_prodotto' => 'required|string|max:255',
            'categoria_id'  => 'required|exists:categorie,id_categoria',
            'durata_id'     => 'required|exists:durata,id_durata',
            'quantita'      => 'required|integer|min:1', 
            'unita_misura'  => 'required|string|in:' . implode(',', UnitaMisura::all())
        ]);

        // Recupero della categoria e durata
        $categoria = Categoria::find($validated['categoria_id']);
        $durata = Durata::find($validated['durata_id']);

        // Aggiunta alla tabella di pivot
        $categoriaDurataId = DB::table('categoria_durata')->insertGetId([
            'id_categoria'    => $validated['categoria_id'], 
            'id_durata'       => $validated['durata_id'], 
            'durata_standard' => $categoria->giorni_categoria,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Calcolo della data di scadenza (moltiplicazione dei giorni della categoria con il moltiplicatore della durata)
        $giorni_categoria = $categoria->giorni_categoria;
        $moltiplicatore_durata = $durata->moltiplicatore_durata;
        
        // Data di scadenza
        $data_scadenza = now()->addDays($giorni_categoria * $moltiplicatore_durata);

        // Creazione del nuovo prodotto nella tabella 'prodotto'
        Prodotto::create([
            'nome_prodotto'       => $validated['nome_prodotto'], 
            'data_scadenza'       => $data_scadenza, 
            'quantita'            => $validated['quantita'], 
            'unita_misura'        => $validated['unita_misura'],
            'id_categoria_durata' => $categoriaDurataId, 
            'created_at'          => now(),
            'updated_at'          => now()
        ]);

        // Redirect con messaggio di successo
        return redirect()->route('add')->with('success', 'Prodotto aggiunto con successo.');
    }
    
}
