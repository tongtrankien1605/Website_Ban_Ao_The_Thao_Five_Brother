<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = [
        'title',
        'published_at',
        'image',
        'author',
        'short_description',
        'content',
    ];
    protected $appends = [
        'published_month',
        'published_day',
        'published_time'
    ];
    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }
    public function getPublishedMonthAttribute()
    {
        return Carbon::parse($this->published_at)->format('F');
    }
    public function getPublishedDayAttribute()
    {
        return Carbon::parse($this->published_at)->format('d');
    }
    public function getPublishedTimeAttribute()
    {
        return Carbon::parse($this->published_at)->format('d M');
    }
}
