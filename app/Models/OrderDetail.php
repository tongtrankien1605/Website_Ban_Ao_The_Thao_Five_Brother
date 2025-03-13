<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';
    protected $fillable = [
        'id_order',
        'id_product_variant',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y/m/d H:i:s');
    }
    public function orders()
    {
        return $this->belongsTo(Order::class,'id_order','id');
    }
    public function product_variants()
    {
        return $this->belongsTo(Skus::class, 'id_product_variant','id');
    }
}
