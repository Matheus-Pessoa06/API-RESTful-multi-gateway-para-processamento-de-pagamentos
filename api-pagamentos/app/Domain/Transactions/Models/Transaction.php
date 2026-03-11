<?php

namespace App\Domain\Transactions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Domain\Product\Models\Product;

class Transaction extends Model
{
    use HasUuids;

    
    protected $fillable = [
        'client_id', 
        'gateway_id', 
        'external_id', 
        'status', 
        'amount', 
        'card_last_numbers'
    ];

    /**
     * Relacionamento N:N com Produtos (via tabela pivô transaction_products)
     * Essencial para o attach() funcionar no seu PaymentService
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class, 
            'transaction_products'
        )
        ->withPivot('quantity')
        ->withTimestamps();
    }
}