<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDispute extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'customer_id',
        'note',
        'phone',
        'resolved_note',
        'resolved_by',
        'resolved_at',
    ];
    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
    public function resolved()
    {
        return $this->belongsTo(User::class, 'resolved_by', 'id');
    }
    public function orders()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
