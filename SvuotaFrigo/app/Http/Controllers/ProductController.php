<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show()
    {
        $prodotto = Prodotto::find(4); // Recupera il prodotto con ID = 4

        
    
        return view('fridge.fridge_dashboard', compact('prodotto'));
    }


    public function getCategoriaProdotto()
    {
        // ID del prodotto da cercare (in questo caso fisso su 4)
        $id_prodotto = 4;

        $prodotto = Prodotto::with('categoria.categoria')->find($id_prodotto);

        return $prodotto->categoria->categoria->nome_categoria;
    }
    
    public function destroy()
    {
        // Recuperiamo il prodotto con id = 4
        $prodotto = Prodotto::findOrFail(4);
        
        // Eliminiamo il prodotto
        $prodotto->delete();

        // Rispondiamo con una risposta JSON per confermare l'eliminazione
        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
        $prodotto = Prodotto::find($request->id_prodotto);
    
        if ($prodotto) {
            $prodotto->nome_prodotto = $request->nome_prodotto;
            $prodotto->data_scadenza = $request->data_scadenza;
            $prodotto->save();
    
            return response()->json(['success' => true, 'message' => 'Prodotto aggiornato con successo!']);
        }
    
        return response()->json(['success' => false, 'message' => 'Prodotto non trovato'], 404);
    }
}
