<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAtributeValue extends Model
{
    use HasFactory;

    protected $table = 'product_atribute_values';
    protected $fillable = [
        'product_id',
        'product_attribute_id',
        'value',
    ];
    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
    public function product_attributes()
    {
        return $this->belongsTo(ProductAtribute::class);
    }
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
}
