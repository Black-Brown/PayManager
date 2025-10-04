<?php

namespace App\Models;

use JosueIsOffline\Framework\Model\Model;

class Activity extends Model
{
    protected string $table = 'extracurricular_activities';

    protected array $fillable = [
        'name',
        'description',
        'monthly_cost',
        'instructor',
        'schedule',
        'active',
        'created_at',
        'updated_at'
    ];
}