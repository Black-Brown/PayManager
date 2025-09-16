<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Verificar si el usuario ya está autenticado
if (isset($_SESSION['usuario_id']) && isset($_SESSION['usuario_rol'])) {
    $rol = $_SESSION['usuario_rol'];
    
    // Redirigir según el rol
    switch ($rol) {
        case 1: // Admin
            header('Location: /pages/index.html.twig');
            exit;
        case 2: // Usuario normal
            header('Location: /pages/index.html.twig');
            exit;
        default: // Rol desconocido
            session_destroy();
            header('Location: /login.html.twig');
            exit;
    }
}

// Si no está autenticado, mostrar login
$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader);
echo $twig->render('pages/login.html.twig');