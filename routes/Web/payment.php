<?php
use App\Controllers\Web\PaymentController;

return [
    ['GET', '/payments', [PaymentController::class, 'index'], 'auth'],
    ['GET', '/payments/create', [PaymentController::class, 'createForm'], 'auth'],
    ['POST', '/payments', [PaymentController::class, 'store'], 'auth'],

    ['GET', '/payments/{id}/edit', [PaymentController::class, 'editForm'], 'auth'],
    ['POST', '/payments/{id}/update', [PaymentController::class, 'update'], 'auth'],

    ['GET', '/payments/{id}/delete', [PaymentController::class, 'destroy'], 'auth'],
];
