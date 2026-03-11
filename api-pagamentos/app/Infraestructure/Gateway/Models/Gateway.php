<?php

namespace App\Infraestructure\Gateway\Models;

use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    protected $fillable = [
        'name', 
        'is_active', 
        'priority'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
