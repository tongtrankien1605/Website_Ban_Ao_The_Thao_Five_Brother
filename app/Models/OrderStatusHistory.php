<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;
    protected $table = 'order_status_histories';
    protected $fillable = [
        'order_id',
        'old_status',
        'new_status',
        'user_id',
        'note',
    ];
    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }

    public function orders()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
