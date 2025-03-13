<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';
    protected $fillable = [
        'id_payment_method_status',
        'name',
    ];

    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }
    public function payment_method_statuses()
    {
        return $this->belongsTo(PaymentMethodStatus::class, 'id_payment_method_status',);
    }
    public function orders()
    {
        return $this->hasMany(Order::class,'id_payment_method','id_payment_method');
    }
}
