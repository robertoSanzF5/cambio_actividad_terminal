<?php

global $user;

if (is_null($user)) {
    //Primera accion de inicio de sesion
    if ($_GET['action'] == 'validar_usuario') {
        //INICIO DE SESIÓN
        $usuario 	=	trim($_POST["usuario"]);
        $password	=	trim($_POST["password"]);

        $user = null;
        $nombre = "";

        $json = [
            'ejecucion' => false,
            'validUser' => false
        ];

        try{
            $user = User::userLogin($usuario, $password);

            switch ($user->getState()) {
                case $user::BLOQUED:
                    $json['error'] = 'bloqueado';
                    break;
                case $user::WRONG:
                    $json['error'] = 'loginError';
                    break;
                case $user::NEED_UPDATE:
                    $json['error'] = 'inseguro';
                    $json['token'] = $user->getToken();
                    $json['userId'] = $user->getUserId();
                    break;
                case $user::LOGED:
                    $json['validUser'] = true;
                    $json['token'] = $user->getToken();
                    $json['userId'] = $user->getUserId();
                    $json['nombre_db']=$user->getCentroActual();
                    break;
                default:
                    $json['error'] = 'loginError';
                    break;
            }

            $json['ejecucion'] = true;
        } catch (PDOException $e){
            capturar_error($e);
            unset($db);

            $json = ['ejecucion' => false];
        }
        $httpContentTytle = "login";
    } else {
        header("HTTP/1.0 403 Usuario no válido.");
        exit();
    }
} else {//Si hay datos de un usuario en la sesion
    if (isset($_REQUEST['categoria']) && $user->getState() == User::LOGED) {
        require_once CONFIG['phpFiles'] . '/action/' . $_REQUEST['categoria'] . '.php';
    } else {
        require_once CONFIG['phpFiles'] . '/action/commons.php';
    }
}

//Si de las acciones se obtiene un JSON, se escriben titulos para el contenido y se envía la respuesta al cliente
if (isset($json)) {
    if (!isset($httpContentTytle) || empty($httpContentTytle)) {
        if (isset($_REQUEST['categoria'])) {
            $httpContentTytle = $_REQUEST['categoria'] . '_' . $_GET['action'];
        } else {
            $httpContentTytle = 'action_' . $_GET['action'];
        }
    }

    //header("Content-type: " . $contentTypeTitle . "/json");
    echo json_encode($json);//Se envia la response por ajax
    die();
}

//Si de las acciones se obtiene un texto, se envía directamente la respuesta al cliente
if (isset($text)) {
    echo $text;
    die();
}

//Si de las acciones se obtiene un fichero, se envía el fichero a través de un link que se recibe en el cliente
if (isset($file) && !empty($file)) {
    $file = CONFIG['raiz'] . '/temp/' . $file;

    if (file_exists($file)) {
        readfile($file);
        unlink($file);
        die();
    }

    header("HTTP/1.0 500 Error al crear el archivo.");
}

header("HTTP/1.0 404 Accion invalida.");
die();
