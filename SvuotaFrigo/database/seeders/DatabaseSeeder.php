<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Durata;
use App\Models\Frigo;
use App\Models\Prodotto;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Categoria::trun
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);



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


        $categoria_frutta->durata()->attach($durata_BT->id_durata, [
            'durata_standard' => 5,
            'immagine_standard' => 'frutta.jpg',
        ]);

        $this->insertProdotti();


    }

    private function insertProdotti()
    {
        $durata_MT = Durata::find(1); // Supponendo che tu stia cercando una durata con id = 1
        $categoria_frutta = Categoria::find(1); // Supponendo che tu stia cercando una categoria con id = 1


        // Creazione del prodotto con i valori necessari per id_categoria e id_durata
        $prodotto_mela = Prodotto::create([
            'nome_prodotto' => 'Mela',
            'data_scadenza' => '2025-01-21',
            'flag_deleted' => 0,
            'id_categoria' => $categoria_frutta->id_categoria, // Collega la categoria corretta
            'id_durata' => $durata_MT->id_durata, // Collega la durata corretta
        ]);

        // Associa il prodotto alla categoria&durata
        $prodotto_mela->setCategoria()->associate($categoria_frutta);
        $prodotto_mela->setDurata()->associate($durata_MT);
        $prodotto_mela->save();

        $user = User::find(1);

        // Creazione del Frigo
        $frigo1 = Frigo::create([
            'id_prodotto' => $prodotto_mela->id_prodotto,
            'id_user' => $user->id
        ]);

        // Associa il Frigo al Prodotto
        $frigo1->setProdotto()->associate($prodotto_mela);
        // Associa il Frigo allo user
        $frigo1->setUser()->associate($user);






    }
}
