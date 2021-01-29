<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'vendor/autoload.php';
include 'bd.php';

$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig = new \Twig\Environment($loader,[ ]);

$variablesTwig = [];

session_start();
if (isset($_SESSION['nickUsuario'])) {
    $variablesTwig['nombre'] = $_SESSION['nickUsuario'];
    $variablesTwig['rango'] = obtenerRango($_SESSION['nickUsuario']);
}

$variablesTwig['eventos'] = obtenerEventos($variablesTwig['rango']);
echo $twig->render('listaeventos.html', $variablesTwig);
?>