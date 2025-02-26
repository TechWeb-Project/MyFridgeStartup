<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    use HasFactory;

    protected $table = 'error_logs';

    protected $fillable = [
        'user_id',
        'type',
        'message'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
