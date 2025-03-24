<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherUser extends Model
{
    use HasFactory;
    protected $table = 'voucher_users';
    protected $fillable = [
        'id_user',
        'id_voucher',
        'used_at',
    ];
    public function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y/m/d H:i:s');
    }

    public function vouchers()
    {
        return $this->belongsTo(Voucher::class,'id_voucher');
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
