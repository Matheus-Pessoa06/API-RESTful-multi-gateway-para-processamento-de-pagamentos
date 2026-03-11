<?php

namespace App\Domain\Client\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * Relacionamento: Um cliente pode ter várias compras (transactions)
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}