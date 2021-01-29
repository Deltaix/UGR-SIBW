<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'vendor/autoload.php';
include 'bd.php';

$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig = new \Twig\Environment($loader);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nick = $_POST['nick'];
    $pass = $_POST['pass'];

    if (checkLogin($nick, $pass)) {
        session_start();
        $_SESSION['nickUsuario'] = $nick;
    }

    header("Location: indice.php");
    exit();
}

echo $twig->render('login.html', []);
?>