<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressUser extends Model
{
    use HasFactory;

    protected $table = 'address_users';

    protected $fillable = [
        'id_user',
        'name',
        'phone',
        'address',
        'is_default',
    ];

    protected function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'id_address');
    }
}
