<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodotto extends Model
{
    use HasFactory;

    protected $table = 'prodotto';

    protected $primaryKey = 'id_prodotto';

    // Abilita l'auto-incremento per la chiave primaria
    public $incrementing = true;

    protected $fillable = ['nome_prodotto', 'data_scadenza', 'flag_deleted', 'id_categoria', 'id_durata'];

    protected $casts = ['data_scadenza' => 'date'];

    // Relazione 1:n con CategoriaDurata (relazione tramite chiavi esterne composite)
    public function setCategoria()
    {
        return $this->belongsTo(CategoriaDurata::class, 'id_categoria', 'id_categoria');
    }

    public function setDurata()
    {
        return $this->belongsTo(Durata::class, 'id_durata', 'id_durata');
    }

}
