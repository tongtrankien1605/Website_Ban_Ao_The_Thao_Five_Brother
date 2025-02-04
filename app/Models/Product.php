<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';
    protected $fillable = [
        'id_brand',
        'name',
        'description',
        'id_category',
        'image',
        'price',
    ];

    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }

    public function brands()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function product_attribute_values()
    {
        return $this->hasMany(ProductAtributeValue::class);
    }

    public function skuses()
    {
        return $this->hasMany(Skus::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
}
