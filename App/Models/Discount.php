<?php

namespace App\Models;

use JosueIsOffline\Framework\Model\Model;

class Discount extends Model
{
    protected string $table = 'discounts';

    protected array $fillable = [
        'payment_id',
        'discount_amount',
        'reason',
        'applied_by_user_id'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function appliedBy()
    {
        return $this->belongsTo(User::class, 'applied_by_user_id');
    }
}
