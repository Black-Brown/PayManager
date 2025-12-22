<?php

namespace App\Models;

use JosueIsOffline\Framework\Model\Model;

class User extends Model
{
  public array $fillable = ['name', 'email', 'password', 'role_id', 'active', 'remember_token'];
}
