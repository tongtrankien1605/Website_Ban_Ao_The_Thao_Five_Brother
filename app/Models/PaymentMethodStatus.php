<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethodStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payment_method_statuses';
    protected $fillable = [
        'name',
    ];
    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }
    public function payment_methods()
    {
        return $this->hasMany(PaymentMethod::class, 'id_payment_method_status');
    }
}
