<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skus extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'skuses';
    protected $fillable = [
        'product_id',
        'name',
        'quantity',
        'price',
        'sale_price',
        'image',
        'barcode',
    ];

    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }

    public function products()
    {
        return $this->belongsTo(Product::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function cart_items()
    {
        return $this->hasMany(CartItem::class);
    }
    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'id_product_variants');
    }
}
