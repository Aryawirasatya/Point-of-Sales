<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['invoice_number', 'total_amount', 'payment_status', 'user_id'];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

        public function user()
    {
        // foreign key di tabel sales memang bernama user_id, 
  
        return $this->belongsTo(User::class);
    }
}
