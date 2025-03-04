<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    public function show(Request $request)
    {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }

        $userId = Auth::id();
        
        // Modifica la query per includere solo i prodotti dell'utente corrente
        $prodotti = DB::table('prodotto')
            ->join('frigo', 'prodotto.id_prodotto', '=', 'frigo.id_prodotto')
            ->where('frigo.id_user', $userId)
            ->get();

        return view('fridge.fridge_dashboard', compact('prodotti'));
    }

    public function getCategoriaProdotto()
    {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }

        // ID del prodotto da cercare (in questo caso fisso su 8)
        $id_prodotto = 8;

        $prodotto = Prodotto::with('categoria.categoria')->find($id_prodotto);

        return $prodotto->categoria->categoria->nome_categoria;
    }
    
    public function destroy($id)
    {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }

        $userId = Auth::id();
        
        DB::table('frigo')
            ->where('id_prodotto', $id)
            ->where('id_user', $userId)
            ->delete();

        return redirect()->back();
    }

    public function update(Request $request)
    {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }

        $prodotto = Prodotto::find($request->id_prodotto);
    
        if ($prodotto) {
            $prodotto->nome_prodotto = $request->nome_prodotto;
            $prodotto->data_scadenza = $request->data_scadenza;
            $prodotto->save();
    
            return response()->json(['success' => true, 'message' => 'Prodotto aggiornato con successo!']);
        }
    
        return response()->json(['success' => false, 'message' => 'Prodotto non trovato'], 404);
    }

    public function getProductDetails(Request $request) 
    {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }

        $id = $request->id;
        $imageName = $request->imageName; // Ricevi il nome dell'immagine
        
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
                'nome' => $prodotto->nome_prodotto,
                'data_scadenza' => $prodotto->data_scadenza->format('d/m/Y'),
                'categoria' => $prodotto->categoria->categoria->nome_categoria,
                'immagine' => asset('images/icone_frigo/' . $imageName) 
            ]
        ]);
    }

    public function store(Request $request)
    {
        if ($authError = $this->checkAuth()) {
            return $authError;
        }

        $userId = Auth::id();
        
        $prodotto = Prodotto::create($request->all());
        
        // Collega il prodotto al frigo dell'utente
        DB::table('frigo')->insert([
            'id_prodotto' => $prodotto->id_prodotto,
            'id_user' => $userId
        ]);

        return redirect()->back();
    }

    public function checkUserAuth(Request $request)
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'user' => Auth::check() ? Auth::user() : null
        ]);
    }
}
