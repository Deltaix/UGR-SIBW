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
    if(isset($_POST["idevento"])) {
        $variablesTwig['evento'] = obtenerEvento($_POST['idevento']);
    }

    if (isset($_POST['idmodificar'])) {
        editarEvento($_POST['nombre'], $_POST['fecha'], $_POST['lugar'], $_POST['texto'], $_FILES['foto'], $_FILES['galeria'], $_POST['facebook'], $_POST['twitter'], $_POST['tag'], $_POST['idmodificar']);
        header('Location: '."http://localhost/evento.php?id=". $_POST['idmodificar']);
        exit();
    }
}

echo $twig->render('editareventos.html', $variablesTwig);
?>