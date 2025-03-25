<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'id_user',
        'phone_number',
        'id_address',
        'id_order_status',
        'id_shipping_method',
        'id_payment_method',
        'id_payment_method_status',
        'total_amount',
        'id_voucher',
    ];

    public function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y/m/d H:i:s');
    }
    public function users()
    {
        return $this->belongsTo(User::class,'id_user','id');
    }
    public function address_users()
    {
        return $this->belongsTo(AddressUser::class, 'id_address');
    }
    public function order_statuses()
    {
        return $this->belongsTo(OrderStatus::class,'id_order_status','id');
    }
    public function shipping_methods()
    {
        return $this->belongsTo(ShippingMethod::class, 'id_shipping_method', 'id_shipping_method');
    }
    public function payment_methods()
    {
        return $this->belongsTo(PaymentMethod::class,'id_payment_method','id_payment_method');
    }
    public function payment_method_statuses()
    {
        return $this->belongsTo(PaymentMethodStatus::class,'id_payment_method_status','id');
    }
    public function vouchers()
    {
        return $this->belongsTo(Voucher::class);
    }
    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'id_order', 'id');
    }
    public function order_status_histories()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id', 'id');
    }
    public function refunds()
    {
        return $this->hasMany(Refund::class,'id_order','id');
    }

}
