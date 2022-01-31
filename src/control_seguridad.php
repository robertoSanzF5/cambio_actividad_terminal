<?php
global $user;

$output = [];
$data = ['url' => CONFIG['url']];

if (is_null($user) && isset($_SESSION)) {
    if (isset($_SESSION)) {
        session_destroy();
    }

    //SI NO HAY USUARIO LA PANTALLA ES EL LOGIN
    $contentTypeTitle = "menuges";
    $js = 'menuges.js';
    $view = 'menuges';
    $screen = 'fmtuser';
} else {
    $data = array_merge($user->getEntornoInfo(), $data);

    //SI HAY UN USUARIO LOGEADO LA PANTALLA POR DEFECTO SERÁ EL MENÚ
    $contentTypeTitle = "menuges01";
    $js = 'menuges01.js';
    $view = 'menuges01';
    $screen = 'fmt01';
}

//CARGAR CONTROLADOR QUE CORRESPONDA DE ACUERDO CON LA PANTALLA REQUERIDA
$control = $_GET['control'];//Se obtiene el nombre del controlador de la pagina que se va a mostrar

//Si en la sesion hay un controlador
if (isset($_SESSION['control']) && $_SESSION['control'] != null) {
    //Si el nombre de la sesion es distinto al nombre del controlador que se quiere mostrar
    if ($control != $_SESSION['control']) {
        if ($control != 'menuges01') {//Si no es del menu principal, el nombre del control anterior se establecera como el actual
            $_SESSION['back'] = $_SESSION['control'];
            $_SESSION['control'] = $control;
        }
    }
} else {
    $_SESSION['control'] = $control;
}

$file = CONFIG['phpFiles'] . '/control/' . $control . '.php';//se intenta obtener el archivo del controlador segun el nombre

if (file_exists($file)) {//si existe, se dirigirá al archivo .php correspondiente y obtener los datos, la vista y la pantalla
    require_once $file;
} elseif ($control == 'menuges01') { //PERMISOS EN BOTONES MENÚ
    $data['btrespres_disabled'] = true;
    $data['btrespres_vl'] = false;

    $data['btconfj_disabled'] = true;
    $data['btconfj_vl'] = false;

    $data['btcoste_disabled'] = true;
    $data['btcoste_vl'] = false;

    $data['btcosteg_disabled'] = true;
    $data['btcosteg_vl'] =false;

    $data['btinftrab_disabled'] = true;
    $data['btinftrab_vl'] = false;

    $data['btcamcen_disabled'] = true;
    $data['btcamcen_vl'] = false;

    $data['btcamhis_disabled'] = true;
    $data['btcamhis_vl'] = false;

    $data['bttra_disabled'] = false;
    $data['bttra_vl'] = true;

    $data['btcuadrante_disabled'] = false;
    $data['btcuadrante_vl'] = true;

    $data['btvac_disabled'] = false;
    $data['btvac_vl'] = true;

    $data['btman_disabled'] = false;
    $data['btman_vl'] = true;

    $data['btpermisos_abc_disabled'] = false;
    $data['btpermisos_abc_vl'] = true;

    $data['btjornada_disabled'] = false;
    $data['btjornada_vl'] = true;

    $data['btrestrab_disabled'] = false;
    $data['btrestrab_vl'] = true;

    $data['btresacti_copy_disabled'] = false;
    $data['btresacti_copy_vl'] = true;

    $data['btresdeta_disabled'] = false;
    $data['btresdeta_vl'] = true;

    $data['btresaus_disabled'] = false;
    $data['btresaus_vl'] = true;

    $data['btdescans_disabled'] = false;
    $data['btdescans_vl'] = true;

    $data['bthoras_e_disabled'] = false;
    $data['bthoras_e_vl'] = true;


    if ($user->getAutorizacionUsuario() != 99) {
        $data['btcierre_disabled'] = true;
        $data['btcierre_color'] = 'gray';
        $data['btgrupos_disabled'] = true;
        $data['btgrupos_color'] = 'gray';
        $data['btfichaje_disabled'] = true;
        $data['btfichaje_color'] = 'gray';
        $data['btcoste_disabled'] = true;
        $data['btcoste_vl'] = false;
    } else {
        $data['btinftrab_vl'] = true;
        $data['btinftrab_disabled'] = false;
        $data['btcoste_disabled'] = false;
        $data['btcoste_vl'] = true;

        /*$userIds = [2, 35, 70, 91];

        if (in_array($user->getUserId(), $userIds))*/
        //Para que se vea el boton reparto costes guadalajara
        $user_coste_g = ['aperez', 'alopez', 'silviah', 'ismaeldm', 'joel', 'rober', 'nataliaac'];
        if (in_array($user->getUsername(), $user_coste_g))
        {
            $data['btcosteg_disabled'] = false;
            $data['btcosteg_vl'] = true;
        }
    }

    /*$userIds = [2, 10, 35, 36, 37, 70, 89, 91, 95];

    if(in_array($user->getUserId(), $userIds))*/
    //Para que se vean todos los menus de la derecha menos reparto_costes_guadalajara
    $user_buttons = ['rrhh2', 'aperez', 'rrhh1', 'alopez', 'davidp', 'silviah',  'tarik', 'joel', 'rober'];

    if (in_array($user->getUsername(), $user_buttons))
    {
        $data['btcamcen_disabled'] = false;
        $data['btcamcen_vl'] = true;

        $data['btcamhis_disabled'] = false;
        $data['btcamhis_vl'] = true;

        $data['btrespres_disabled'] = false;
        $data['btrespres_vl'] = true;

        $data['btconfj_disabled'] = false;
        $data['btconfj_vl'] = true;

        $data['btrespres_disabled'] = false;
        $data['btrespres_vl'] = true;

       // $data['btpermisos_abc_disabled'] = true;
       // $data['btpermisos_abc_color'] = 'gray';

    }

    if ($user->getAutorizacionUsuario() == 80) {
        $data['btcierre_disabled'] = false;
        $data['btcierre_color'] = '';
        $data['btgrupos_disabled'] = false;
        $data['btgrupos_color'] = '';
        $data['btfichaje_disabled'] = false;
        $data['btfichaje_color'] = '';
        $data['btpermisos_abc_disabled'] = true;
        $data['btpermisos_abc_color'] = 'gray';
    }

    switch($user->getAutorizacionUsuario())
    {
        case 10: //RecepcionLME
            $data['btgrupos_disabled'] = true;
            $data['btgrupos_color'] = 'gray';
            $data['btfichaje_disabled'] = true;
            $data['btfichaje_color'] = 'gray';
            $data['btactividad_disabled'] = true;
            $data['btactividad_color'] = 'gray';
            $data['btman_disabled'] = true;
            $data['btman_color'] = 'gray';
            $data['btcierre_disabled'] = false;
            $data['btcierre_color'] = '';
            $data['btpermisos_abc_disabled'] = true;
            $data['btpermisos_abc_color'] = 'gray';
            break;
        case 11: //ProblemLME
            $data['btgrupos_disabled'] = true;
            $data['btgrupos_color'] = 'gray';
            $data['btfichaje_disabled'] = true;
            $data['btfichaje_color'] = 'gray';
            $data['btactividad_disabled'] = true;
            $data['btactividad_color'] = 'gray';
            $data['btman_disabled'] = true;
            $data['btman_color'] = 'gray';
            $data['btcierre_disabled'] = false;
            $data['btcierre_color'] = '';
            $data['btpermisos_abc_disabled'] = true;
            $data['btpermisos_abc_color'] = 'gray';
            break;
        case 12: //SalidasLME
            $data['btgrupos_disabled'] = true;
            $data['btgrupos_color'] = 'gray';
            $data['btfichaje_disabled'] = true;
            $data['btfichaje_color'] = 'gray';
            $data['btactividad_disabled'] = true;
            $data['btactividad_color'] = 'gray';
            $data['btman_disabled'] = true;
            $data['btman_color'] = 'gray';
            $data['btcierre_disabled'] = false;
            $data['btcierre_color'] = '';
            $data['btpermisos_abc_disabled'] = true;
            $data['btpermisos_abc_color'] = 'gray';
            break;
        case 13: //PreparacionLME
            $data['btgrupos_disabled'] = true;
            $data['btgrupos_color'] = 'gray';
            $data['btfichaje_disabled'] = true;
            $data['btfichaje_color'] = 'gray';
            $data['btactividad_disabled'] = true;
            $data['btactividad_color'] = 'gray';
            $data['btman_disabled'] = true;
            $data['btman_color'] = 'gray';
            $data['btcierre_disabled'] = false;
            $data['btcierre_color'] = '';
            $data['btpermisos_abc_disabled'] = true;
            $data['btpermisos_abc_color'] = 'gray';
            break;
        case 14: //GsabcLME
            $data['btactividad_disabled'] = true;
            $data['btactividad_color'] = 'gray';
            $data['btman_disabled'] = true;
            $data['btman_color'] = 'gray';
            $data['btproc_disabled'] = true;
            $data['btproc_color'] = 'gray';
            $data['btcierre_disabled'] = false;
            $data['btcierre_color'] = '';
            $data['btpermisos_abc_disabled'] = true;
            $data['btpermisos_abc_color'] = 'gray';
            break;
        case 01:
            $data['bttra_disabled'] = true;
            $data['bttra_color'] = 'gray';
            $data['btcuadrante_disabled'] = true;
            $data['btcuadrante_color'] = 'gray';
            $data['btvac_disabled'] = true;
            $data['btvac_color'] = 'gray';
            $data['btman_disabled'] = true;
            $data['btman_color'] = 'gray';
            $data['btjornada_disabled'] = true;
            $data['btjornada_color'] = 'gray';
            $data['btrestrab_disabled'] = true;
            $data['btrestrab_color'] = 'gray';
            $data['btresacti_copy_disabled'] = true;
            $data['btresacti_copy_color'] = 'gray';
            $data['btresdeta_disabled'] = true;
            $data['btresdeta_color'] = 'gray';
            $data['btresaus_disabled'] = true;
            $data['btresaus_color'] = 'gray';
            $data['btdescans_disabled'] = true;
            $data['btdescans_color'] = 'gray';
            $data['bthoras_e_disabled'] = true;
            $data['bthoras_e_color'] = 'gray';
            $data['btpermisos_abc_disabled'] = true;
            $data['btpermisos_abc_color'] = 'gray';
        break;
        case 02:
            $data['bttra_disabled'] = true;
            $data['bttra_color'] = 'gray';
            $data['btvac_disabled'] = true;
            $data['btvac_color'] = 'gray';
            $data['btman_disabled'] = true;
            $data['btman_color'] = 'gray';
            $data['btjornada_disabled'] = true;
            $data['btjornada_color'] = 'gray';
            $data['btrestrab_disabled'] = true;
            $data['btrestrab_color'] = 'gray';
            $data['btresacti_copy_disabled'] = true;
            $data['btresacti_copy_color'] = 'gray';
            $data['btresdeta_disabled'] = true;
            $data['btresdeta_color'] = 'gray';
            $data['btresaus_disabled'] = true;
            $data['btresaus_color'] = 'gray';
            $data['btdescans_disabled'] = true;
            $data['btdescans_color'] = 'gray';
            $data['bthoras_e_disabled'] = true;
            $data['bthoras_e_color'] = 'gray';
            $data['btpermisos_abc_disabled'] = true;
            $data['btpermisos_abc_color'] = 'gray';
            break;

    }

}

if (isset($controller)) {//Si se crea un controlador, se almacenará como array para devolverlo al cliente
    $output['controller'] = 'index.php?control=' .$controller;
}

//PASAMOS LOS ARCHIVOS JS QUE SE HAYAN AGREGADO
$path = CONFIG['public'] . '/jscss/';

if (gettype($js) == 'array') {
    $indice = 'extjs';

    foreach ($js as $clave => $valor) {//Si estan almacenados en un array, se enviaran como un array de js con indices
        //$file = 'jscss/' . $valor . '?v=' . getLastModifyFile($path . $valor);
        $file = 'jscss/' . $valor . '?v=' . time();
        $temp_index = $indice;

        if ($clave > 0) {
            $temp_index .= $clave + 1;
        }

        $data[$temp_index] = $file;
    }
} elseif (gettype($js) == 'string') {//Si estan almacenados en un string, se almacenara en un string normal
    $data['extjs'] = 'jscss/' . $js . '?v=' . getLastModifyFile($path . $js);
}


//PASAMOS ARCHIVOS CSS
if (isset($css)) {
    //$path = CONFIG['public'] . '/jscss/';

    if (gettype($css) == 'array') {
        foreach ($css as $clave => $valor) {
            $file = 'jscss/' . $valor . '?v=' . getLastModifyFile($path.$valor);

            $indice = 'extcss';

            if ($clave > 0) {
                $indice .= $clave+1;
            }

            $data[$indice] = $file;
        }
    } elseif (gettype($css) == 'string') {
        $data['extcss'] = 'jscss/' . $css . '?v=' . getLastModifyFile($path.$css);
    }
}

//$output['data'] = array_merge($entornoInfo, $data);
$output['data'] = $data;
$output['view'] = 'jsonfiles/' . $view . '.json';
$output['screen'] = $screen;

//header("Content-type: " . $contentTypeTitle . "/json");
//Se envian los datos al cliente para crear la página
echo json_encode($output);
die();
