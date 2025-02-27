<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAtributeValue extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'product_atribute_values';
    protected $fillable = [
        'product_attribute_id',
        'value',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y/m/d',
        'updated_at' => 'datetime:Y/m/d'
    ];
    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
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
