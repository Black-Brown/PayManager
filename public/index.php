<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Cargar las vistas desde la carpeta /views
$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader);

// Renderizar la plantilla index.html.twig
echo $twig->render('pages/index.html.twig');
