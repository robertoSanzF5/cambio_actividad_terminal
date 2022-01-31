<?php
require_once '../src/config.php';

$json = [
    'saved' => false
];

$usuario = isset($_REQUEST['usuario']) ? $_REQUEST['usuario'] : null;
$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : null;

$user = User::checkUserLoged($usuario, $token);

if ($user != null) {
    if (isset($_GET['action'])) {
        if (isset($_POST['categoria'])) {
            require_once CONFIG['phpFiles'] . '/action/' . $_REQUEST['categoria'] . '.php';
        } else {
            require_once CONFIG['phpFiles'] . '/action/commons.php';
        }
    } else {
        $json['error'] = 'Petición no valida.';
    }
} else {
    $json['error'] = 'Usuario incorrecto.';
}

echo json_encode($json);