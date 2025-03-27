<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'category_id', 'brand_id','stock', 'discount','image'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'sale_detail')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}
