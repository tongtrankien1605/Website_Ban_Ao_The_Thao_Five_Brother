<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAtribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_atributes';
    protected $fillable = [
        'name',
    ];

    public function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y/m/d H:i:s');
    }

    public function product_atribute_values()
    {
        return $this->hasMany(ProductAtributeValue::class);
    }
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
    public function values()
    {
        return $this->hasMany(ProductAtributeValue::class, 'product_attribute_id');
    }

}
