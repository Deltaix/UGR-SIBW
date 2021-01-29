<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'vendor/autoload.php';
include 'bd.php';

header('Content-Type: application/json');

session_start();

$variablesTwig[] = "";
$variablesTwig['rango'] = "";

if (isset($_SESSION['nickUsuario'])) {
    $variablesTwig['nombre'] = $_SESSION['nickUsuario'];
    $variablesTwig['rango'] = obtenerRango($_SESSION['nickUsuario']);
}

$eventos = obtenerBusqueda($_POST['peti'], $variablesTwig['rango']);

echo(json_encode($eventos));
?>