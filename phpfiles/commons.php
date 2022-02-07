<?php
function getClientIp()
{
//    if (defined('CONFIG') && isset(CONFIG['clientIp']) && !empty(CONFIG['clientIp'])) {
    if (defined('CONFIG') && null !== CONFIG['clientIp'] && !empty(CONFIG['clientIp'])) {
        $clientIp = CONFIG['clientIp'];
    } else {
        if (getenv('HTTP_CLIENT_IP')) {
            $clientIp = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $clientIp = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            $clientIp = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            $clientIp = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
            $clientIp = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            $clientIp = getenv('REMOTE_ADDR');
        } else {
            $clientIp = 'UNKNOWN';
        }
    }

    return $clientIp;
}

function getServerHost()
{
    if (defined('CONFIG') && null !== CONFIG['url'] && !empty(CONFIG['url'])) {
        $url = CONFIG['url'];
    } else {
        $url = 'http://' . $_SERVER['SERVER_NAME'];
        //'url' => 'http://localhost/abc_postgress/public',

        if ($_SERVER['SERVER_PORT'] != 80) {
            $url .= ':' . $_SERVER['SERVER_PORT'];
        }
    }

    return $url;
}

function registerLog($log, $text)
{
    $path = CONFIG['raiz'] . '/log';

    if (!is_dir($path)) {
        mkdir($path);
    }

    $path .= "/$log.log";

    if (file_exists($path)) {
        $text = PHP_EOL . $text;
    }

    $file = fopen($path, 'a');

    fwrite($file, $text);

    fclose($file);
}

function capturar_error($error, $proceso = '')
{
    $text = date('Y-m-d H:i:s');

    if (empty($proceso) && defined('PROCESO')) {
        $proceso = PROCESO;
    }

    if (!empty($proceso) && $proceso != 'FICHAJE' && $proceso != 'EVALOS') {
        $text .= ' - ' . $proceso;
    }

    $text .= PHP_EOL . $error . PHP_EOL;

    if ($proceso == 'FICHAJE') {
        registerLog('fichaje_error', $text);
    } elseif ($proceso == 'EVALOS') {
        registerLog('evalos_error', $text);
    } else {
        registerLog('php_error', PHP_EOL . $text);
    }
}

function getJsonFile($view)
{
    $jsonFile = CONFIG['src'] . '/jsonfiles/' . $view . '.json';

    //COMPROBACIONES DE USUARIOS?

    if (file_exists($jsonFile)) {
        header("Content-type: " . $view . "/json");
        require_once $jsonFile;
    } else {
        header("HTTP/1.0 404 Archivo de vista no existe.");
    }
}

function generar_csv($nombre, $grid, $cabecera)
{
    $dir = CONFIG['raiz'] . '/temp/';
    checkDir($dir);
    //var_dump($dir);
    $file = fopen($dir . $nombre, 'w');

    if ($return = ($file !== false)) {
        if ($return = (fputcsv($file, $cabecera, ";") !== false)) {
            foreach ($grid as $campo) {
                if (isset($campo['bccolor'])) {
                    unset($campo['bccolor']);
                }

                if (isset($campo['bccaja'])) {
                    unset($campo['bccaja']);
                }

                if (fputcsv($file, $campo, ";") === false) {
                    $return = false;
                    break;
                }
            }
        }

        fclose($file);

        if (!$return) {
            unlink($dir . $nombre);
        }
    }

    return $return;
}

function go_back()
{
    $user = User::getCurrentUser();

    if ($user == null || $user->getState() != User::LOGED) {
        return 'login';
    }

    if (isset($_SESSION['back'])) {
        $control = $_SESSION['back'];
    } else {
        $control = 'menuges01';
    }

    unset($_SESSION['control']);
    unset($_SESSION['back']);

    return $control . '&usuario=' . $user->getUserId() . '&token=' . $user->getToken();
}

/**
 * Comprueba que la carpeta existe. Si no existe la crea.
 * @param string $path
 */
function checkDir($path)
{
    $dir = '';

    if (substr_compare($path, CONFIG['public'] . '/', 0, strlen(CONFIG['public'])) === 0) {
        $dir = CONFIG['public'];
    } elseif (substr_compare($path, CONFIG['phpFiles'] . '/', 0, strlen(CONFIG['phpFiles'])) === 0) {
        $dir = CONFIG['phpFiles'];
    } elseif (substr_compare($path, CONFIG['src'] . '/', 0, strlen(CONFIG['src'])) === 0) {
        $dir = CONFIG['src'];
    } elseif (substr_compare($path, CONFIG['raiz'] . '/', 0, strlen(CONFIG['raiz'])) === 0) {
        $dir = CONFIG['raiz'];
    }

    if (!empty($dir)) {
        $path = substr($path, strlen($dir));
    }

    $dirs = explode('/', $path);

    if ($dirs[0] == '') {
        $dirs = array_slice($dirs, 1);
    }

    if ($dirs[count($dirs) - 1] == '') {
        $dirs = array_slice($dirs, 0, -1);
    }

    foreach ($dirs as $d) {
        $dir .= '/' . $d;
        if (!file_exists($dir)) {
            mkdir($dir);
        }
    }
}

function clean_string($string)
{
    //Quitar espacios
    $string = str_replace(' ', '', $string);

    // Quitar caracteres especiales
    $string = preg_replace('/[^A-Za-z0-9\ ]/', '', $string);

    return $string;
}

/**
 * Obtiene ultima fecha modificacion del archivo en formato Unix
 * @param string $path
 * @return bool|false|int
 */
function getLastModifyFile($path)
{
    if (file_exists($path)) {
        return filemtime($path);
    }

    return false;
}

function random_str($length, $keyspace = 'abcdefghijklmnopqrstuvwxyz')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    if ($max < 1) {
        throw new Exception("");
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[mt_rand(0, $max)];
    }
    return $str;
}

//FUNCIONES FECHAS Y HORAS
/**
 * @param int $minutos
 * @return string
 */
function minutos_a_horas($minutos)
{
    $horas = intval($minutos / 60);
    $minresto = $minutos % 60;

    return substr('0' . $horas, -2) . ':' . substr('0' . $minresto, -2);
}

function diferencia_microsegundos($date1, $date2)
{
    $diff = abs(strtotime($date1->format('d-m-Y H:i:s.u')) - strtotime($date2->format('d-m-Y H:i:s.u')));

//Creates variables for the microseconds of date1 and date2
    $micro1 = $date1->format("u");
    $micro2 = $date2->format("u");

//Absolute difference between these micro seconds:
    $micro = abs($micro1 - $micro2);

//Creates the variable that will hold the seconds (?):
    return $diff . "." . $micro;
}

function segundos_a_horas($segundos)
{
    $horas = intval($segundos / 3600);
    $horasEnSegundos = $horas * 3600;
    $segundos = $segundos - $horasEnSegundos;

    $minutos = intval($segundos / 60);
    $minutosEnSegundos = $minutos * 60;
    $segundos = $segundos -  $minutosEnSegundos;


    return $horas . 'h:' . $minutos . 'm:' . $segundos . 's';
}

function conversorDMAHMS($tms)
{
    $resul = '- ABIERTO';

    if ($tms !== '-') {
        try {
            $dt = new DateTime($tms);

            $resul = $dt->format('d-m-y H:i:s');
        } catch (Exception $e) {
            $resul = '';
        }
    }

    return $resul;
}

function validateDate($date, $format = 'd-m-Y')
{
    $d = DateTime::createFromFormat($format, $date);

    if ($d === false || $d->format($format) != $date) {
        return false;
    }

    return $d;
}

function get_datos_csv($path, $cabecera) {
    $datos = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (count($datos) == 0) {
        return "El fichero se encuentra vacío.";
    }

    $header_fichero = explode(';', $datos[0]);

    if (count($header_fichero) != count($cabecera)) {
        return "Número de campos incorrecto.";
    }

    if ($cabecera !== $header_fichero) {
        return "Cabecera incorrecta";
    }

    return $datos;
}

/**
 * @param $time     int
 * @param $centro   Centro
 * @param null $resto
 */
function round_time_context($time, &$resto = 0) {
    $c = User::getCurrentUser()->getCentroActual();
    $mins_media_hora = $c->getMinutosParaMediaHora();
    $mins_una_hora = $c->getMinutosParaUnaHora();
    $time = intval($time);

    $rounded = intval($time/60);
    $resto += $time%60;

    if ($resto >= $mins_una_hora) {
        $rounded += 1;
        $resto -= 60;
    } elseif ($resto >= $mins_media_hora) {
        $rounded += 0.5;
        $resto -= $mins_media_hora;
    }

    return $rounded;
}
