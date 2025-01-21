<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frigo extends Model
{
    use HasFactory;

    protected $table = 'frigo';

    protected $primaryKey = 'id_frigo';

    // Abilita l'auto-incremento per la chiave primaria
    public $incrementing = true;

    // Disabilita la gestione dei timestamps
    public $timestamps = false;

    protected $fillable = ['id_prodotto', 'id_user'];

    // Relazione 1:n con Prodotto
    public function setProdotto()
    {
        return $this->belongsTo(Prodotto::class, 'id_prodotto', 'id_prodotto');
    }

    // Relazione 1:n con Users
    public function setUser()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
