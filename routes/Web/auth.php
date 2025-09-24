<?php
use App\Controllers\Web\AuthController;

return [
    ['GET', '/login', [AuthController::class, 'loginForm']],
    ['POST', '/login', [AuthController::class, 'login']],

    ['GET', '/register', [AuthController::class, 'registerForm']],
    ['POST', '/register', [AuthController::class, 'register']],
    
    ['GET', '/logout', [AuthController::class, 'logout']],
];
