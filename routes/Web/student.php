<?php
use App\Controllers\Web\StudentController;

return [
    ['GET', '/students', [StudentController::class, 'index'], 'auth'],
    ['GET', '/students/create', [StudentController::class, 'createForm'], 'auth'],
    ['POST', '/students', [StudentController::class, 'store'], 'auth'],

    ['GET', '/students/{id}/edit', [StudentController::class, 'editForm'], 'auth'],
    ['POST', '/students/{id}/update', [StudentController::class, 'update'], 'auth'],

    ['GET', '/students/{id}/delete', [StudentController::class, 'destroy'], 'auth'],
];
