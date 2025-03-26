<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'roles';

    protected $fillable =[
        'user_role',
    ];

    protected function serializeDate($date) {
        return $date->format('Y/m/d H:i:s');
    }

    public function users() {
        return $this->hasMany(User::class,'role','id');
    }
}
