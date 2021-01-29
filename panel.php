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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newpass = $_POST['newpass'];
    $newpass2 = $_POST['newpass2'];
    $oldpass = $_POST['oldpass'];

    if ($newpass == $newpass2) {
        actualizarPass($newpass, $oldpass, $_SESSION['nickUsuario']);
    }
}

echo $twig->render('panel.html', $variablesTwig);
?>