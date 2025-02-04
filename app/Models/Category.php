<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';
    protected $fillable = [
        'name',
        'description',
        'image',
        'is_active',
    ];

    public $attribute = [
        'is_active' => 0,
    ];

    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
