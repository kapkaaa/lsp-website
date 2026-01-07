<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'role_id', 'name', 'username', 'password', 
        'nik', 'address', 'city', 'phone', 'status'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function approvedOrders()
    {
        return $this->hasMany(Order::class, 'approved_by');
    }
    
    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }
    
    public function isCashier()
    {
        return $this->role->name === 'cashier';
    }
    
    public function isCustomer()
    {
        return $this->role->name === 'customer';
    }
}