<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends Model
{
    use HasFactory;
    protected $table = 'shipping_methods';
    protected $fillable = [
        'name',
        'cost',
        'estimated_time',
        'status',
    ];
    public function serializeDate($date) {
        return $date->format('Y/m/d H:i:s');
    }
    public function orders() {
        return $this->hasMany(Order::class, 'id_shipping_method','id_shipping_method');
    }
}
