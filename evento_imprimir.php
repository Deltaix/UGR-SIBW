<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'vendor/autoload.php';
include 'bd.php';

$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig = new \Twig\Environment($loader,[ ]);

$idEvento = intval($_GET['id']);

$evento = obtenerEvento($idEvento);

echo $twig->render('evento_imprimir.html',['evento' => $evento]);

?>