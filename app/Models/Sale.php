<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['total', 'user_id', 'payment_type_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function voucher()
    {
        return $this->hasOne(Voucher::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'sale_detail')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}
