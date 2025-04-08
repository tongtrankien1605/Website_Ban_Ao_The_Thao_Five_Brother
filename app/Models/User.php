<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users';

    protected $fillable = [
        'phone_number',
        'email',
        'password',
        'name',
        'avatar',
        'gender',
        'birthday',
        'role',
        'status',
        'email_verified_at',
        'remember_token',
        'is_locked',
        'locked_until'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts =
    [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:Y/m/d H:i:s',
        'birthday' => 'datetime:Y/m/d H:i:s',
        'password' => 'hashed',
        'is_locked' => 'boolean',
        'locked_until' => 'datetime'
    ];


    protected function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $timeFormat = Carbon::now()->format('dm');
            $model->email = $model->email . '_' . $timeFormat;
            $model->phone_number = $model->phone_number . '_' . $timeFormat;
            $model->save();
        });
        static::deleted(function ($model) {});
        static::created(function ($model) {});
    }

    public function address_users()
    {
        return $this->hasMany(AddressUser::class,'id_user','id');
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, 'role', 'id');
    }

    public function voucher_users()
    {
        return $this->hasMany(VoucherUser::class);
    }
    public function order_status_histories()
    {
        return $this->hasMany(OrderStatusHistory::class, 'user_id', 'id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_user', 'id');
    }
    public function inventory_logs()
    {
        return $this->hasMany(InventoryLog::class, 'user_id', 'id');
    }
    public function inventory_entries()
    {
        return $this->hasMany(InventoryEntry::class, 'user_id', 'id');
    }
    /**
     * Check if the user's account is currently locked
     *
     * @return bool
     */
    public function isLocked(): bool
    {
        if (!$this->is_locked) {
            return false;
        }

        if ($this->locked_until && $this->locked_until->isPast()) {
            $this->unlock();
            return false;
        }

        return true;
    }

    /**
     * Lock the user's account for a specified number of hours
     *
     * @param int $hours
     * @return bool
     */
    public function lock(int $hours = 24): bool
    {
        $this->is_locked = true;
        $this->locked_until = now()->addHours($hours);
        return $this->save();
    }

    /**
     * Unlock the user's account
     *
     * @return bool
     */
    public function unlock(): bool
    {
        $this->is_locked = false;
        $this->locked_until = null;
        return $this->save();
    }

    public function paymentAttempts()
    {
        return $this->hasMany(PaymentAttempt::class, 'user_id');
    }
    public function approver()
    {
        return $this->hasMany(InventoryEntry::class, 'id_shopper', 'id');
    }
    
}
