<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\CategoriaDurata;
use App\Models\Durata;
use App\Models\Prodotto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * php artisan migrate:fresh --seed
     * Usare il comando per droppare DB e aggiungere dati default
     */
    public function run(): void
    {
        $categoria_frutta = Categoria::create([
            'nome_categoria' => 'Frutta',
            'giorni_categoria' => 5
        ]);

        $categoria_carne = Categoria::create([
            'nome_categoria' => 'Carne',
            'giorni_categoria' => 3
        ]);

        $durata_BT = Durata::create([
            'moltiplicatore_durata' => 1,
            'nome_durata' => 'BREVE_TERMINE'
        ]);

        $durata_MT = Durata::create([
            'moltiplicatore_durata' => 2,
            'nome_durata' => 'MEDIO_TERMINE'
        ]);

        $durata_LT = Durata::create([
            'moltiplicatore_durata' => 3,
            'nome_durata' => 'LUNGO_TERMINE'
        ]);

        $categoria_carne->durata()->attach($durata_MT->id_durata, [
            'durata_standard' => 6,
            'immagine_standard' => 'carne.jpg',
        ]);

        $categoria_carne->save();


        $categoria_frutta->durata()->attach($durata_BT->id_durata, [
            'durata_standard' => 5,
            'immagine_standard' => 'frutta.jpg',
        ]);

        $categoria_frutta->save();



        $categoriaDurata1 = CategoriaDurata::find(1);
        $prodotto1 = Prodotto::create([
            'nome_prodotto' => 'bistecca',
            'data_scadenza' => now(),
            'id_categoria_durata' => $categoriaDurata1->id
        ]);
        // Associa il prodotto alla categoria&durata
        $prodotto1->categoria()->associate($categoriaDurata1);


        $categoriaDurata2 = CategoriaDurata::find(2);
        $prodotto2 = Prodotto::create([
            'nome_prodotto' => 'pesce',
            'data_scadenza' => now(),
            'id_categoria_durata' => $categoriaDurata2->id
        ]);

        // Associa il prodotto alla categoria&durata
        $prodotto2->categoria()->associate($categoriaDurata2);

    }


}
