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
    if(isset($_POST["idcomentario"])) {
        $variablesTwig['comentario'] = obtenerComentario($_POST['idcomentario']);
    }

    if (isset($_POST['ftext'])) {
        editarComentario($_POST['idcomentario'], $_POST['ftext']);
        header('Location: '."http://localhost/evento.php?id=". $_POST['idevento']);
        exit();
    }
}

echo $twig->render('editarcomentarios.html', $variablesTwig);
?>