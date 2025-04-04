<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryEntry extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'inventory_entries';
    protected $fillable = [
        'id_skus',
        'user_id',
        'quantity',
        'cost_price',
        'price',
        'sale_price',
        'discount_start',
        'discount_end',
        'status'
    ];
    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }
    public function skuses()
    {
        return $this->belongsTo(Skus::class, 'id_skus', 'id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'id_shopper', 'id');
    }
    public function inventory_logs()
    {
        return $this->hasMany(InventoryLog::class, 'inventory_entry_id', 'id');
    }
}
