<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'refund_id',
        'user_id',
        'note',
        'image',
    ];
    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function refund()
    {
        return $this->belongsTo(Refund::class, 'refund_id', 'id');
    }
}
