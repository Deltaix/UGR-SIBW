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

if(!empty($_POST)){
    aniadirEvento($_POST['nombre'], $_POST['fecha'], $_POST['lugar'], $_POST['texto'], $_FILES['foto'], $_FILES['galeria'], $_POST['facebook'], $_POST['twitter'], $_POST['tag']);
    $_POST = array();
    header('Location: indice.php');
    exit();
}

echo $twig->render('aniadireventos.html', $variablesTwig);
?>