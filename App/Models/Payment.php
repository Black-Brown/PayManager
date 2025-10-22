<?php

namespace App\Models;

use JosueIsOffline\Framework\Model\Model;

class Payment extends Model
{
    protected string $table = 'payments';

    protected array $fillable = [
        'student_id',
        'concept_id',
        'amount',
        'payment_date',
        'corresponding_month',
        'payment_method',
        'reference',
        'notes',
        'registered_by_user_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function concept()
    {
        return $this->belongsTo(PaymentConcept::class, 'concept_id');
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }
}
