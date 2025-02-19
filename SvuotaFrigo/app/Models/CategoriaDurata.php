<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaDurata extends Model
{
    use HasFactory;

    protected $table = 'categoria_durata';

    protected $fillable = ['durata_standard', 'immagine_standard'];

    // public function categoriaDurata()
    // {
    //     return $this->belongsToMany(CategoriaDurata::class, 'categoria_durata', 'id_prodotto', 'id_categoria');
    // }

    // Relazione 1:N con i Prodotti
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }


    // Relazione 1:N con i Prodotti
    public function prodotti()
    {
        return $this->hasMany(Prodotto::class, 'id_categoria_durata', 'id');
    }
}
