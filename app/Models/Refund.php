<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_order',
        'old_status',
        'reason',
        'refund_amount',
        'refund_quantity',
        'status',
        'image_path',
        'video_path',
        'bank_account',
        'bank_name',
        'account_holder_name'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function refund_history()
    {
        return $this->hasOne(RefundHistory::class, 'refund_id', 'id');
    }
}
