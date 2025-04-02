<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_order',
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
}
