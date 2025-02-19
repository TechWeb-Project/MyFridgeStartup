<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show()
    {
        $prodotto = Prodotto::find(2); // Recupera il prodotto con ID = 2
        return view('fridge.product_details', compact('prodotto'));
    }

    public function destroy()
    {
        // Recuperiamo il prodotto con id = 1
        $prodotto = Prodotto::findOrFail(2);

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
