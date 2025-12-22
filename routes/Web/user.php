<?php

use App\Controllers\Web\UserController;

return [
  ['GET', '/users', [UserController::class, 'index'], 'auth'],
  ['GET', '/users/create', [UserController::class, 'createForm'], 'auth'],
  ['POST', '/users', [UserController::class, 'store'], 'auth'],

  ['GET', '/users/{id}/edit', [UserController::class, 'editForm'], 'auth'],
  ['POST', '/users/{id}/update', [UserController::class, 'update'], 'auth'],

  ['GET', '/users/{id}/delete', [UserController::class, 'destroy'], 'auth'],
];
