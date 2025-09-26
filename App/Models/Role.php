<?php

namespace App\Models;

use JosueIsOffline\Framework\Model\Model;

class Role extends Model
{
    protected string $table = 'roles';

    protected array $fillable = [
        'name',
        'description'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
