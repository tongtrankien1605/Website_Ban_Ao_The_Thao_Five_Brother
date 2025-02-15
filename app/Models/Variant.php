<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends Model
{
    use HasFactory;
    protected $table = 'variants';
    protected $fillable = [
        'product_id',
        'id_skus',
        'product_attribute_id',
        'product_attribute_value_id',
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
    public function product_atribute_values()
    {
        return $this->belongsTo(ProductAtributeValue::class);
    }
    public function skuses()
    {
        return $this->belongsTo(Skus::class);
    }
}
