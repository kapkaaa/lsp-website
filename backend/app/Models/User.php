<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'username',
        'password',
        'nik',
        'address',
        'city',
        'phone',
        'status',
        'email'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function approvedOrders()
    {
        return $this->hasMany(Order::class, 'approved_by');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function sentChats()
    {
        return $this->hasMany(CustomerServiceChat::class, 'sender_id');
    }

    public function receivedChats()
    {
        return $this->hasMany(CustomerServiceChat::class, 'receiver_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Helper Methods
    public function isAdmin()
    {
        return $this->role->name === 'Admin';
    }

    public function isKasir()
    {
        return $this->role->name === 'Cashier';
    }

    public function isCustomer()
    {
        return $this->role->name === 'Customer';
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role->name, $roles);
        }
        return $this->role->name === $roles;
    }
}