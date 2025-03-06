<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorie';
    public $primaryKey = 'id_categoria';

    // Abilita l'auto-incremento per la chiave primaria
    public $incrementing = true;

    public $fillable = ['nome_categoria', 'giorni_categoria'];

    public function durata()
    {
        return $this->belongsToMany(Durata::class, 'categoria_durata', 'id_categoria', 'id_durata')
            ->withPivot('durata_standard', 'immagine_standard')
            ->withTimestamps();
    }

    // Modifica -> rivedere comunque anche parte sopra per la visilbilità
    public function categoriaDurata()
    {
        return $this->hasMany(CategoriaDurata::class, 'id_categoria', 'id_categoria');
    }

    public function prodotti()
    {
        return $this->hasManyThrough(
            Prodotto::class,
            CategoriaDurata::class,
            'id_categoria',             // Chiave esterna in CategoriaDurata che si collega a Categoria
            'id_categoria_durata',      // Chiave esterna in Prodotto che si collega a CategoriaDurata
            'id_categoria',             // Chiave primaria in Categoria
            'id'                        // Chiave primaria in CategoriaDurata
        );
    }
}
