<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    private function checkAuth()
    {
        $auth = Auth::check();
        if (!$auth) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return null;
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkUserAuth(Request $request)
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'user' => Auth::check() ? Auth::user() : null
        ]);
    }
  
    public function destroy(Request $request)
    {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }

        $userId = Auth::id();

        $id = $request->input('id_prodotto'); // Accedi all'ID in modo sicuro
        // Trova il prodotto nel database
        $prodotto = Prodotto::find($id);
    
        if (!$prodotto) {
            return response()->json(['success' => false, 'message' => 'Prodotto non trovato.'], 404);
        }

        DB::table('frigo')
            ->where('id_prodotto', $id)
            ->where('id_user', $userId)
            ->delete();
    
        // Elimina il prodotto
        $prodotto->delete();
    
        return response()->json(['success' => true, 'message' => 'Prodotto eliminato con successo.']);
    }

/*
    if ($authError = $this->checkAuth()) {
        return $authError;
    }

    $userId = Auth::id();
*/

    public function updateProduct(Request $request)
    {
        //if ($authError = $this->checkAuth()) {
        //    return $authError;
        //}

        $id = $request->id_prodotto;

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
        $imageName = $request->imageName; 
        
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
