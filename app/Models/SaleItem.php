<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = ['sale_id', 'product_id', 'quantity', 'unit_price', 'total_price'];

        public function sale()
    {
        // Ini relasi ke Sale, foreign key sale_id
        return $this->belongsTo(Sale::class);
    }

        public function product()
    {
        // Ini relasi ke Product, foreign key product_id
        return $this->belongsTo(Products::class, 'product_id');
    }
}