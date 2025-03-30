<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vouchers';
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'total_usage',
        'start_date',
        'end_date',
        'status',
        'max_discount_amount'
    ];
    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }
    public function voucher_users()
    {
        return $this->hasMany(VoucherUser::class);
    }
}
