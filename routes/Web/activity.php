<?php
use App\Controllers\Web\ActivitiesController;

return [
    ['GET', '/activities', [ActivitiesController::class, 'index'], 'auth'],
    ['GET', '/activities/create', [ActivitiesController::class, 'createForm'], 'auth'],
    ['POST', '/activities', [ActivitiesController::class, 'store'], 'auth'],

    ['GET', '/activities/{id}/edit', [ActivitiesController::class, 'editForm'], 'auth'],
    ['POST', '/activities/{id}/update', [ActivitiesController::class, 'update'], 'auth'],

    ['GET', '/activities/{id}/delete', [ActivitiesController::class, 'destroy'], 'auth'],
];
