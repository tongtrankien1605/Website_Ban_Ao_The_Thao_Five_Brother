<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;
    protected $table = 'inventory_logs';
    protected $fillable = [
        'id_product_variant',
        'change_quantity',
        'old_quantity',
        'new_quantity',
        'user_id',
        'reason',
    ];
    public function serializeDate($date)
    {
        return $date->format('Y/m/d H:i:s');
    }
    public function skuses()
    {
        return $this->belongsTo(Skus::class, 'id_product_variant', 'id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function inventory_entries()
    {
        return $this->belongsTo(InventoryEntry::class,'inventory_entry_id','id');
    }
}
