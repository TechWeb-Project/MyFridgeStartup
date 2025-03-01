<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    public function destroy(Request $request)
    {
        $id = $request->input('id_prodotto'); // Accedi all'ID in modo sicuro
        Log::info('Metodo HTTP:', ['method' => $request->method()]);
        Log::info('ID ricevuto:', ['id' => $id]);
    
        // Trova il prodotto nel database
        $prodotto = Prodotto::find($id);
    
        if (!$prodotto) {
            return response()->json(['success' => false, 'message' => 'Prodotto non trovato.'], 404);
        }
    
        // Elimina il prodotto
        $prodotto->delete();
    
        return response()->json(['success' => true, 'message' => 'Prodotto eliminato con successo.']);
    }

    public function updateProduct(Request $request)
    {
        $id = $request->id_prodotto; // Accedi all'ID dal corpo della richiesta
        Log::info("ID prodotto ricevuto: " . $request); // Logga l'ID per vedere cosa arriva

        $prodotto = Prodotto::find($id);

        if (!$prodotto) {
            return response()->json([
                'success' => false,
                'message' => 'Prodotto non trovato'
            ], 404);
        }
    
        // Aggiorna il prodotto
        $prodotto->nome_prodotto = $request->nome_prodotto;
        $prodotto->data_scadenza = $request->data_scadenza;
        $prodotto->quantita = $request->quantita;
        $prodotto->unita_misura = $request->unita;
        $prodotto->save();
    
        return response()->json([
            'success' => true,
            'id' => $id,
            'message' => 'Prodotto aggiornato con successo!',
            'product' => [
                'id' => $id,
                'nome' => $prodotto->nome_prodotto,
                'data_scadenza' => $prodotto->data_scadenza->format('d/m/Y'),
                'quantita' => $prodotto->quantita,
                'unita' => $prodotto->unita_misura
            ]
        ]);
    }
    
    

    public function getProductDetails(Request $request) 
    {
        $id = $request->id;
        $imageName = $request->imageName; // Ricevi il nome dell'immagine
        Log::info("ID prodotto ricevuto nella visualizzazine: " . $id);
        
        $prodotto = Prodotto::with('categoria.categoria')
            ->where('id_prodotto', $id)
            ->first();
        
        if (!$prodotto) {
            return response()->json([
                'success' => false,
                'message' => 'Prodotto non trovato'
            ], 404);
        }
    
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $id, // Passa anche l'ID
                'nome' => $prodotto->nome_prodotto,
                'data_scadenza' => $prodotto->data_scadenza->format('d/m/Y'),
                'quantita' => $prodotto->quantita, 
                'unita' => $prodotto->unita_misura,
                'categoria' => $prodotto->categoria->categoria->nome_categoria,
                'immagine' => asset('images/icone_frigo/' . $imageName) 
            ]
        ]);
    }
}
