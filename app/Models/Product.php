<?php

namespace App\Models;

use App\Http\Controllers\Admin\ProductAttributeController;
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
    public function skuses()
    {
        return $this->hasMany(Skus::class, 'product_id', 'id');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'product_id', 'id');
    }

    public function attributes()
    {
        return $this->hasManyThrough(
            ProductAttributeController::class, 
            ProductAtributeValue::class, 
            'product_id', 
            'id', 
            'id',
            'attribute_id' 
        );
    }
    public function attributeValues()
    {
        return $this->hasManyThrough(
            ProductAtributeValue::class, // Bảng đích (chứa value)
            Variant::class, // Bảng trung gian (chứa product_id)
            'product_id', // Khóa ngoại trong bảng variants trỏ đến products
            'id', // Khóa chính của bảng product_attribute_value
            'id', // Khóa chính của bảng products
            'product_attribute_value_id' // Khóa ngoại trong bảng variants trỏ đến product_attribute_value
        );
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function defaultImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_default', true);
    }
}
