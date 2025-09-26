<?php
use App\Controllers\Web\GradeController;

return [
    ['GET', '/grades', [GradeController::class, 'index'], 'auth'],
    ['GET', '/grades/create', [GradeController::class, 'createForm'], 'auth'],
    ['POST', '/grades', [GradeController::class, 'store'], 'auth'],
    
    ['GET', '/grades/{id}/edit', [GradeController::class, 'editForm'], 'auth'],
    ['POST', '/grades/{id}/update', [GradeController::class, 'update'], 'auth'],

    ['GET', '/grades/{id}/delete', [GradeController::class, 'destroy'], 'auth'],
];
