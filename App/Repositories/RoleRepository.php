<?php

namespace App\Repositories;

use JosueIsOffline\Framework\Database\DB;

class RoleRepository
{
  protected string $table = 'roles';

  public function getAll()
  {
    return DB::table($this->table)
      ->select()
      ->get();
  }
}
