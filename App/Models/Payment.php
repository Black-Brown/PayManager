<?php

namespace App\Models;

use JosueIsOffline\Framework\Model\Model;
use JosueIsOffline\Framework\Database\DB;

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

    /**
     * Obtener todos los descuentos de este pago
     */
    public function discounts()
    {
        return DB::table('discounts')->where('payment_id', $this->id)->get();
    }

    /**
     * Obtener el monto total después de aplicar descuentos
     */
    public function getTotalAmount()
    {
        $discounts = $this->discounts();
        $totalDiscounts = 0;
        
        foreach ($discounts as $discount) {
            $totalDiscounts += $discount['discount_amount'];
        }
        
        return $this->amount - $totalDiscounts;
    }

    /**
     * Verificar si el pago tiene descuentos
     */
    public function hasDiscounts()
    {
        return count($this->discounts()) > 0;
    }

    /**
     * Sobrescribir __get para manejar propiedades computadas
     */
    public function __get($name)
    {
        // Si se accede a total_amount, calcularlo
        if ($name === 'total_amount') {
            return $this->getTotalAmount();
        }
        
        // Para otras propiedades, usar el comportamiento por defecto
        // pero evitar errores si la propiedad no existe
        try {
            return parent::__get($name);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Relaciones
     */
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