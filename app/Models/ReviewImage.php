<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_url',
        'id_review'
    ];

    public function review()
    {
        return $this->belongsTo(Review::class, 'id_review');
    }
}
