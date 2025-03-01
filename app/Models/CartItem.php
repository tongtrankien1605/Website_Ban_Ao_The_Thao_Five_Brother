<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_items';
    protected $fillable = [
        'id_cart',
        'id_product_variant',
        'quantity',
        'id_user',
        'price',
    ];
    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }
    public function carts()
    {
        return $this->belongsTo(Cart::class,'id_cart');
    }

    public function skuses()
    {
        return $this->belongsTo(Skus::class,'id_product_variant');
    }
}
