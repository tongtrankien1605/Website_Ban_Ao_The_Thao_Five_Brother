<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'brands';
    protected $fillable = [
        'name',
    ];

    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_brand');
    }
}
