<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodotto extends Model
{
    use HasFactory;

    protected $table = 'prodotto';

    protected $primaryKey = 'id_prodotto';


    public $incrementing = true;

    protected $fillable = [
        'nome_prodotto',
        'data_scadenza',
        'quantita',
        'unita_misura',
        'id_categoria_durata'
    ];

    protected $casts = ['data_scadenza' => 'date'];

    // Relazione 1:n con CategoriaDurata (relazione tramite chiavi esterne composite)
    public function categoria()
    {
        return $this->belongsTo(CategoriaDurata::class, 'id_categoria_durata', 'id');
    }

}
