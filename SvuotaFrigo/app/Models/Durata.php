<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Durata extends Model
{
    use HasFactory;

    protected $table = 'durata';

    protected $primaryKey = 'id_durata';

    // Abilita l'auto-incremento per la chiave primaria
    public $incrementing = true;

    protected $fillable = ['moltiplicatore_durata', 'nome_durata'];

    public function categorie()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_durata', 'id_durata', 'id_categoria')
            ->withPivot('durata_standard', 'immagine_standard')
            ->withTimestamps();
    }
}
