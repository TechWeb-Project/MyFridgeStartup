<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $table = 'saved_recipes';

    protected $fillable = [
        'user_id',
        'recipe_name',
        'ingredients',
        'instructions',
        'time',
        'num_people',
        'created_at',
        'updated_at',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}