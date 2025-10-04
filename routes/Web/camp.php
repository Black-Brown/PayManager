<?php
use App\Controllers\Web\CampController;

return [
    ['GET', '/camps', [CampController::class, 'index'], 'auth'],
    ['GET', '/camps/create', [CampController::class, 'createForm'], 'auth'],
    ['POST', '/camps', [CampController::class, 'store'], 'auth'],

    ['GET', '/camps/{id}/edit', [CampController::class, 'editForm'], 'auth'],
    ['POST', '/camps/{id}/update', [CampController::class, 'update'], 'auth'],

    ['GET', '/camps/{id}/delete', [CampController::class, 'destroy'], 'auth'],
];
