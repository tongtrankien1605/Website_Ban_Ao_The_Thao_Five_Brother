<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;

    protected $table = 'order_histories';

    protected $fillable = [
        'order_id',
        'user_id',
        'note_user',
        'note_admin',
        'image',
        'bank_account',
        'bank_name',
        'account_holder_name',
        'status',
    ];

    public function orders()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
