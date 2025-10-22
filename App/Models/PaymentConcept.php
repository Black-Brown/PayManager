<?php

namespace App\Models;

use JosueIsOffline\Framework\Model\Model;

class PaymentConcept extends Model
{
    protected string $table = 'payment_concepts';

    protected array $fillable = [
        'name',
        'type',
        'amount',
        'discount_applicable',
        'frequency',
        'active'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'concept_id');
    }
}
