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
        $prodotto = Prodotto::with('categoria.categoria')
            ->where('id_prodotto', $id)
            ->get();
        
        // Debug message to pass to the view
        $debugMessage = "Sono nel metodo di Andrea";

        if ($prodotto->isEmpty()) {
            return redirect()->back()->with('error', 'Prodotto non trovato');
        }

        // Another debug message
        $debugMessage .= " | Ho trovato il prodotto: " . $prodotto->first()->nome; // Assuming the product has a 'nome' attribute

        // Pass it to the view
        return view('fridge.fridge_dashboard', compact('prodotto'));
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
