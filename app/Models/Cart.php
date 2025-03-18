<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $fillable = [
        'id_user',
    ];
    public function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y/m/d H:i:s');
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function cart_items()
    {
        return $this->hasMany(CartItem::class,'id_cart');
    }
}
