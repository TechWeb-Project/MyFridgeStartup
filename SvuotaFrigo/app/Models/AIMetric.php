<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIMetric extends Model
{
    use HasFactory;

    protected $table = 'ai_metrics';

    protected $fillable = [
        'generation_time',
        'success_rate',
        'cpu_usage',
        'memory_usage'
    ];

    public $timestamps = true;
}