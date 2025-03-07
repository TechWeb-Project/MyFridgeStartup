<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\CategoriaDurata;
use App\Models\Durata;
use App\Models\Prodotto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $categorie = [
            ['id_categoria' => 1, 'nome_categoria' => 'Latticino', 'giorni_categoria' => 5],
            ['id_categoria' => 2, 'nome_categoria' => 'Carne', 'giorni_categoria' => 2],
            ['id_categoria' => 3, 'nome_categoria' => 'Pesce', 'giorni_categoria' => 2],
            ['id_categoria' => 4, 'nome_categoria' => 'Frutta', 'giorni_categoria' => 3],
            ['id_categoria' => 5, 'nome_categoria' => 'Verdura', 'giorni_categoria' => 3],
            ['id_categoria' => 6, 'nome_categoria' => 'Cereale', 'giorni_categoria' => 30],
            ['id_categoria' => 7, 'nome_categoria' => 'Pane', 'giorni_categoria' => 3],
            ['id_categoria' => 8, 'nome_categoria' => 'Prodotto forno', 'giorni_categoria' => 3],
            ['id_categoria' => 9, 'nome_categoria' => 'Bevanda', 'giorni_categoria' => 7],
            ['id_categoria' => 10, 'nome_categoria' => 'Conserva', 'giorni_categoria' => 365],
            ['id_categoria' => 11, 'nome_categoria' => 'Condimento', 'giorni_categoria' => 30],
            ['id_categoria' => 12, 'nome_categoria' => 'Legume', 'giorni_categoria' => 30],
            ['id_categoria' => 13, 'nome_categoria' => 'Proteina vegetale', 'giorni_categoria' => 3],
            ['id_categoria' => 14, 'nome_categoria' => 'Dolce', 'giorni_categoria' => 7],
            ['id_categoria' => 15, 'nome_categoria' => 'Snack', 'giorni_categoria' => 7],
        ];

        foreach ($categorie as &$categoria) {
            $categoria['created_at'] = Carbon::parse('2025-02-06 11:32:03');
            $categoria['updated_at'] = Carbon::parse('2025-02-06 15:51:24');
        }

        Categoria::insert($categorie);

        // Dati per la tabella "durate"
        $durate = [
            ['id_durata' => 1, 'nome_durata' => 'Breve durata', 'moltiplicatore_durata' => 1],
            ['id_durata' => 2, 'nome_durata' => 'Media durata', 'moltiplicatore_durata' => 2],
            ['id_durata' => 3, 'nome_durata' => 'Lunga durata', 'moltiplicatore_durata' => 15],
        ];

        foreach ($durate as &$durata) {
            $durata['created_at'] = Carbon::parse('2025-02-06 11:43:41');
            $durata['updated_at'] = Carbon::parse('2025-02-06 16:11:41');
        }

        Durata::insert($durate);
    }
}
