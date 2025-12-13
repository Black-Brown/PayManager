<?php

use App\Controllers\Web\PaymentController;
use App\Controllers\Web\DiscountController;
use App\Controllers\Web\MonthlyPaymentController;

return [
    // === RUTAS DE PAGOS ===
    ['GET', '/payments', [PaymentController::class, 'index'], 'auth'],
    ['GET', '/payments/create', [PaymentController::class, 'createForm'], 'auth'],
    ['POST', '/payments', [PaymentController::class, 'store'], 'auth'],
    
    // Ver detalle de un pago
    ['GET', '/payments/{id}', [PaymentController::class, 'show'], 'auth'],
    
    // Editar pago
    ['GET', '/payments/{id}/edit', [PaymentController::class, 'editForm'], 'auth'],
    ['POST', '/payments/{id}/update', [PaymentController::class, 'update'], 'auth'],
    
    // Eliminar pago
    ['GET', '/payments/{id}/delete', [PaymentController::class, 'destroy'], 'auth'],
    
    // Pagos por estudiante
    ['GET', '/payments/student/{studentId}', [PaymentController::class, 'byStudent'], 'auth'],
    
    // Reporte de pagos (comentado por ahora)
    // ['GET', '/payments/report', [PaymentController::class, 'report'], 'auth'],

    // // === CONTROL DE MENSUALIDADES ===
    // ['GET', '/payments/monthly', [MonthlyPaymentController::class, 'index'], 'auth'],
    // ['GET', '/payments/monthly/create/{studentId}/{month}', [MonthlyPaymentController::class, 'create'], 'auth'],
    
    // === RUTAS DE DESCUENTOS ===
    // Listar descuentos de un pago
    ['GET', '/payments/{paymentId}/discounts', [DiscountController::class, 'index'], 'auth'],
    
    // Aplicar descuento
    ['POST', '/payments/{paymentId}/discount', [DiscountController::class, 'store'], 'auth'],
    
    // Editar descuento
    ['GET', '/payments/{paymentId}/discount/{discountId}/edit', [DiscountController::class, 'edit'], 'auth'],
    ['POST', '/payments/{paymentId}/discount/{discountId}/update', [DiscountController::class, 'update'], 'auth'],
    
    // Eliminar descuento
    ['GET', '/payments/{paymentId}/discount/{discountId}/delete', [DiscountController::class, 'destroy'], 'auth'],
];