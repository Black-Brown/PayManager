<?php

namespace App\Models;

use JosueIsOffline\Framework\Model\Model;

class Student extends Model
{
    protected string $table = 'students';

    protected array $fillable = [
        'ministry_id', 'first_name', 'last_name', 'birth_date', 'gender', 'grade_id', 'guardian_name', 'guardian_phone'
    ];
}