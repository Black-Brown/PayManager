<?php

namespace App\Models;

use JosueIsOffline\Framework\Model\Model;

class Camp extends Model
{
  protected string $table = 'camps';

    protected array $fillable = [
        'name', 'description', 'start_date', 'end_date', 'cost', 'active'
    ];
}