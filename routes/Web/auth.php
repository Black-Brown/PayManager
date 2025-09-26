<?php
use App\Controllers\Web\AuthController;

return [
    ['GET', '/login', [AuthController::class, 'loginForm']],
    ['POST', '/login', [AuthController::class, 'login']],
    ['GET', '/logout', [AuthController::class, 'logout']],
];
