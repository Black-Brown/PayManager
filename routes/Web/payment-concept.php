<?php
use App\Controllers\Web\PaymentConceptController;

return [
    // Rutas principales de conceptos de pago
    ['GET', '/payment-concepts', [PaymentConceptController::class, 'index'], 'auth'],
    ['GET', '/payment-concepts/create', [PaymentConceptController::class, 'createForm'], 'auth'],
    ['POST', '/payment-concepts', [PaymentConceptController::class, 'store'], 'auth'],
    
    // Ver detalle de un concepto
    ['GET', '/payment-concepts/{id}', [PaymentConceptController::class, 'show'], 'auth'],
    
    // Editar concepto
    ['GET', '/payment-concepts/{id}/edit', [PaymentConceptController::class, 'editForm'], 'auth'],
    ['POST', '/payment-concepts/{id}/update', [PaymentConceptController::class, 'update'], 'auth'],
    
    // Eliminar concepto
    ['GET', '/payment-concepts/{id}/delete', [PaymentConceptController::class, 'destroy'], 'auth'],
    
    // Rutas adicionales
    ['GET', '/payment-concepts/{id}/toggle-status', [PaymentConceptController::class, 'toggleStatus'], 'auth'],
    ['GET', '/payment-concepts/{id}/duplicate', [PaymentConceptController::class, 'duplicate'], 'auth'],
];
