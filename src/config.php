<?php
require_once 'phpFiles/funciones/commons.php';

//ESTABLECEMOS ZONA HORARIA MADRID
date_default_timezone_set("Europe/Madrid");

$dbhost = '10.201.5.107';
//$database = 'ABC_integracion';
$database = 'ABC_TEST';
$evalosHost = '10.201.4.102';

if (!isset($_SESSION)) {
    //SESIONES DE 24 HORAS
    ini_set("session.cookie_lifetime","86400");
    ini_set("session.gc_maxlifetime","86400");

    session_start();
}

if (!isset($pg_user)) {
    $pg_user = "abc_app";
    //$pg_user = "jonvergara";
}
if (!isset($pg_password)) {
    $pg_password = "Tqdk36Z2NKIpONjz1meo";
    //$pg_password = "FAGAvrYvBPaegIkRZ65S";
}

$config = [
    'clientIp'  => getClientIp(),
    'lang'      => 'ESP',
    'centro'    => '40',
    'url'       => getServerHost(),
    //'raiz'      => __DIR__ . '/..',
    'raiz'      => $_SERVER['DOCUMENT_ROOT'] . '/..',
    'db'        => [
        'host'      => $dbhost,
        'database'  => $database,
        'user'      => $pg_user,
        'password'  => $pg_password,
        'timeout'   => "100000"
    ],
    'evalos'    => [
        'host'      => $evalosHost,
        'database'  => 'FACTOR5',
        'user'      => 'evalos',
        'password'  => 'evalosadm'
    ],
    'method_encrypt'
    => 'md5',
    'debug'     => [
        'query'     => false,
        'requests'  => false
    ],
    'mail'      => [
        'smtpHost'  => 'smtp.office365.com',
        'smtpPort'  => '587',
        'mailSend'  => 'alertas@factorcinco.com',
        'mailPass'  => '4NdFYryzFteAedx7',
        'rrhhMail'  => 'recursoshumanos@factorcinco.com'
    ]
];

//$config['public'] = $config['raiz'] . '/public';
$config['public'] = $_SERVER['DOCUMENT_ROOT'];
$config['src'] = __DIR__; //$config['raiz'] . '/src';
$config['phpFiles'] = __DIR__ . '/phpFiles';//$config['src'] . '/phpFiles';
$config['libs'] = $config['phpFiles'] . '/libs';
$config['funciones'] = $config['phpFiles'] . '/funciones';

//DEFINIMOS CONFIGURACION COMO CONSTANTE
define('CONFIG', $config);
unset($config);

require_once CONFIG['phpFiles'] . '/Objetos/ConexDB.php';
require_once CONFIG['phpFiles'] . '/Objetos/Centro.php';
require_once CONFIG['phpFiles'] . '/Objetos/User.php';