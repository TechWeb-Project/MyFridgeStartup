<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request)
    {
        // Get the product ID from the request
        $id = $request->route('id');
        
        // Load the product with its relationships
        $prodotti = Prodotto::with('categoria.categoria')
            ->where('id_prodotto', $id)
            ->get();
    
        if ($prodotti->isEmpty()) {
            return redirect()->back()->with('error', 'Prodotto non trovato');
        }
    
        // Pass it to the view as 'prodotti' to match the view's expectations
        return view('fridge.fridge_dashboard', compact('prodotti'));
    }

    public function getCategoriaProdotto()
    {
        // ID del prodotto da cercare (in questo caso fisso su 8)
        $id_prodotto = 8;

        $prodotto = Prodotto::with('categoria.categoria')->find($id_prodotto);

        return $prodotto->categoria->categoria->nome_categoria;
    }
    
    public function destroy()
    {
        // Recuperiamo il prodotto con id = 8
        $prodotto = Prodotto::findOrFail(8);
        
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
