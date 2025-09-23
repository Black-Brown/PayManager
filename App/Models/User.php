<?php

namespace App\Models;

use JosueIsOffline\Framework\Model\Model;

class User extends Model
{
  protected string $table = 'users';

    protected array $fillable = [
        'name', 'email', 'password', 'role_id', 'active', 'remember_token'
    ];

    protected array $hidden = ['password', 'remember_token'];

    protected array $casts = [
        'active' => 'boolean'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}