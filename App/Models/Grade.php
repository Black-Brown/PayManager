<?php

namespace App\Models;

use JosueIsOffline\Framework\Model\Model;

class Grade extends Model
{
  protected string $table = 'grades';

    protected array $fillable = [
        'name', 'level', 'grade_order'
    ];
}