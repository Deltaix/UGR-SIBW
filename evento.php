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

$idEvento = intval($_GET['id']);

$variablesTwig['evento'] = obtenerEvento($idEvento);
$variablesTwig['comentarios'] = obtenerComentarios($idEvento);
$variablesTwig['palabrasBanned'] = obtenerBanned();
$variablesTwig['galeria'] = obtenerImagenes($idEvento);

if(!empty($_POST)){
    if(isset($_POST["ftext"])){
        nuevoComentario($_SESSION['nickUsuario'], $_POST['ftext'], $idEvento);
        header('Location: '."http://localhost/evento.php?id=".$idEvento);
    }

    if(isset($_POST["idcomentario"])) {
        borrarComentario($_POST["idcomentario"]);
        header('Location: '."http://localhost/evento.php?id=".$idEvento);
    }

    if(isset($_POST["idborrar"])) {
        borrarEvento($_POST["idborrar"]);
        header("Location: indice.php");
        exit();
    }
}

echo $twig->render('evento.html', $variablesTwig);
?>