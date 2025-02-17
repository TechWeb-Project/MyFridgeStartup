<?php
namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VisualizzatoreFrigoController extends Controller
{
    public function mostraFrigo()
    {
        // Preleva i prodotti che hanno una data di scadenza, ordinati per scadenza
        $prodotti = Prodotto::whereNotNull('data_scadenza')
            ->orderBy('data_scadenza', 'asc')
            ->get();

        return view('fridge.fridge_dashboard', compact('prodotti'));
    }
}