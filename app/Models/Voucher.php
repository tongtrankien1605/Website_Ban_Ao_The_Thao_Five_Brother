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
    protected $casts =
    [ 
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime:Y/m/d H:i:s',
        'updated_at' => 'datetime:Y/m/d H:i:s',
    ];
    public function voucher_users()
    {
        return $this->hasMany(VoucherUser::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_voucher', 'id');
    }
    protected $attribute = [
        'status' => 0,
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (is_null($model->max_discount_amount)) {
                $model->max_discount_amount = $model->discount_value;
            }
        });
    }
}
