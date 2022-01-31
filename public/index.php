<?php
require_once '../src/config.php';

$usuario = isset($_REQUEST['usuario']) ? $_REQUEST['usuario'] : null;
$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : null;

$db = ConexDB::getDb();

$user = User::checkUserLoged($usuario, $token);

if (is_null($user) && !is_null($usuario) && !is_null($token) && !isset($_GET['control']) && !isset($_GET['action'])) {
    header('Location: ' . CONFIG['url']);
    die();
}

try {
    if (isset($_GET['control'])) {
        require_once "../src/control.php";
    }

    if (isset($_GET['action'])) {
        require_once "../src/action.php";
    }

    require_once "../src/view.php";
} catch (Exception $e) {
    header("HTTP/1.0 500 Error.");

    $text = '########################################' . PHP_EOL;
    $text .= 'URL CONSULTADA: ' . $_SERVER['QUERY_STRING'] . PHP_EOL;
    $text .= 'PARAMETROS: ' . PHP_EOL;

    if (isset($_POST)) {
        foreach ($_POST as $nombre => $valor) {
            $text .= "     - " . $nombre . ": " . $valor . PHP_EOL;
        }
    }

    $text .= 'HORA: ' . date('Y-m-d H:i:s') . PHP_EOL;
    $text .= 'MENSAJE: ' . PHP_EOL;

    $text .= $e;

    capturar_error($text);
}