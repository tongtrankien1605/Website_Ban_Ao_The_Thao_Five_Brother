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
        'total_amount',
        'id_voucher',
    ];

    public function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y/m/d H:i:s');
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function address_users()
    {
        return $this->belongsTo(AddressUser::class, 'id_address');
    }
    public function order_statuses()
    {
        return $this->belongsTo(OrderStatus::class);
    }
    public function shipping_methods()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
    public function payment_methods()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function vouchers()
    {
        return $this->belongsTo(Voucher::class);
    }
    public function order_details(){
        return $this->hasMany(OrderDetail::class);
    }

}
