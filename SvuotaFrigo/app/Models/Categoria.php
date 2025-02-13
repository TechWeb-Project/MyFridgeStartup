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
}
