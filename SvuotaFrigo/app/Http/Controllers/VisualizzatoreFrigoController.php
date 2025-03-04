<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Constants\UnitaMisura;
use Illuminate\Support\Facades\Auth;

class VisualizzatoreFrigoController extends Controller
{
    public function mostraFrigo()
    {
        $userId = Auth::id();

        // Modifica la query per includere solo i prodotti dell'utente corrente
        $prodotti = Prodotto::with('categoria.categoria')
            ->join('frigo', 'prodotto.id_prodotto', '=', 'frigo.id_prodotto')
            ->where('frigo.id_user', $userId)
            ->whereNotNull('prodotto.data_scadenza')
            ->orderBy('prodotto.data_scadenza', 'asc')
            ->get();

        // Per ogni prodotto, assegna l'immagine in base al nome della categoria,
        // calcola il bollino di scadenza e aggiorna l'unità di misura.
        foreach ($prodotti as $prodotto) 
        {
            $nomeCategoria = $prodotto->categoria->categoria->nome_categoria;
            $prodotto->immagine = $this->getImmagineForCategoria($nomeCategoria);

            // Calcola e assegna il bollino di scadenza
            $this->assegnaBollinoScadenza($prodotto);

            // Aggiorna l'unità di misura del prodotto
            $this->aggiornaUnitaMisura($prodotto);
        }

        // Passa i prodotti alla vista
        return view('fridge.fridge_dashboard', compact('prodotti'));
    }

    /**
     * Mappa il nome della categoria all'immagine corrispondente.
     *
     * @param string $nomeCategoria
     * @return string
     */
    private function getImmagineForCategoria(string $nomeCategoria): string
    {
        $categorieImmagini = [
            'Latticino'           => 'dairy-products.png',
            'Carne'               => 'meat.png',
            'Pesce'               => 'fish.png',
            'Frutta'              => 'fruit.png',
            'Verdura'             => 'vegetable.png',
            'Cereale'             => 'wheat-sack.png',
            'Pane'                => 'bread-loafs.png',
            'Prodotto da forno'   => 'bakery.png',
            'Bevanda'             => 'soft-drink.png',
            'Conserva'            => 'canned-food.png',
            'Condimento'          => 'sauces.png',
            'Legume'              => 'edamame.png',
            'Proteina vegetale'   => 'soy-meat.png',
            'Dolce'               => 'roll-cake.png',
            'Snack'               => 'ice-cream-sandwich.png',
        ];

        return $categorieImmagini[$nomeCategoria] ?? 'default.png';
    }

    /**
     * Assegna il bollino di scadenza in base alla differenza di giorni.
     *
     * @param Prodotto $prodotto
     * @return void
     */
    private function assegnaBollinoScadenza($prodotto)
    {
        $now = Carbon::today();
        $expDate = Carbon::parse($prodotto->data_scadenza)->startOfDay();
        $daysDiff = (int) $now->diffInDays($expDate, false);

        if ($expDate->lt($now)) {
            $prodotto->dotClass = 'dot-red';  // Prodotto scaduto
        } elseif ($daysDiff <= 2) {
            $prodotto->dotClass = 'dot-orange'; // Bollino arancione
        } else {
            $prodotto->dotClass = 'dot-green'; // Bollino verde
        }
    }

    /**
     * Aggiorna l'unità di misura del prodotto in base alla costante.
     *
     * @param Prodotto $prodotto
     * @return void
     */
    private function aggiornaUnitaMisura($prodotto)
    {
        switch ($prodotto->unita_misura) {
            case UnitaMisura::GRAMMI:
                $prodotto->unita_misura = 'gr';
                break;
        
            case UnitaMisura::ML:
                $prodotto->unita_misura = 'ml';
                break;
        
            case UnitaMisura::FETTE:
                $prodotto->unita_misura = 'ftt';
                break;
        
            case UnitaMisura::PEZZI:
                $prodotto->unita_misura = 'pz';
                break;
        }
    }
}
