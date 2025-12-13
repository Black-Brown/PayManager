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

    public static function enrollmentConcepts()
    {
        return self::where('type', 'Enrollment')->where('active', true)->get();
    }

    public static function reenrollmentConcepts()
    {
        return self::where('type', 'Reenrollment')->where('active', true)->get();
    }

    public static function getByType($type)
    {
        return self::where('type', $type)->where('active', true)->get();
    }

    public static function monthlyConcepts()
    {
        return self::where('type', 'Monthly')->where('active', true)->get();
    }

    public function allowsDiscount()
    {
        return $this->discount_applicable;
    }
}
