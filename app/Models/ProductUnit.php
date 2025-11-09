<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'is_default',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_default' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
