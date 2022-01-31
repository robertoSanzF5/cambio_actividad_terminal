<?php
global $user;

if ($user->getState() == User::LOGED) {
    switch ($_GET['action']) {
        case 'changeControl':
            if (!isset($_POST['control'])) {
                break;
            }

            $validControls = [
                'login',                    'menuges01',                'ver_trabajadores',
                'mantenimientos',           'cuadrante',                'grupos_actividades',
                'presencia_centro',         'cierre_jornada',           'fichajes_evalos',
                'consulta_vacaciones',      'res_trabajador',           'res_actividad',
                'res_detalle_actividad',    'res_ausentismo',           'res_descansos',
                'res_horas_extra',          'res_presencia',            'res_jornada_confirmacion',
                'reparto_costes',           'res_cambios_centro',       'res_motivo_cambio_historico',
                'res_inf_trabajador',       'ver_jornadas',             'actividades_integrar',
                'reparto_costes_guada',     'permisos_abc',             'res_detalle_trabajador'
            ];

            $text = '';

            $control = $_POST['control'];

            if (in_array($control, $validControls)) {
                $controlFile = CONFIG['phpFiles'] . '/control/' . $control . '.php';

                if (file_exists($controlFile)) {
                    if (isset($_SESSION['control'])) {
                        $_SESSION['back'] = $_SESSION['control'];
                    }

                    $_SESSION['control'] = $control;
                    $text = 'index.php?control=' . $control . '&usuario=' . $user->getUserId() . '&token=' . $user->getToken();
                    if (isset($_POST["fechaCierreJornada"])){
                        $text .= '&fecha='.$_POST["fechaCierreJornada"];
                    }
                }
            }
            break;
        case 'cambio_centro':
            if ($user->setCentro($_POST['centro'])) {
                $text = "OK";
            } else {
                $text = "NO OK";
            }
            break;
        case 'last_file_modify':
            //OBTENEMOS LA ULTIMA FECHA DE MODIFICACIÓN DE UN ARCHIVO JS O CSS DE LA WEB
            if (isset($_GET['file'])) {
                $text = getLastModifyFile(CONFIG['public'] . '/jscss/' . $_GET['file']);
            }
            break;
        case 'getTimeServer':
            //phpfiles/control_acceso_time_server.php
            //phpfiles/scontrol_acceso_time_server.php
            //phpfiles/xcontrol_acceso_time_server.php
            $json = [
                'date' => date('Y-m-d'),
                'dateDma' => date('d-m-Y'),
                'time' => date('H:i:s')
            ];
            break;
    }
}

if ($user->getState() == User::LOGED || $user->getState() == User::NEED_UPDATE) {
    switch ($_GET['action']) {
        case 'cambiarPassword':
            if ($user->changePassword($_POST['password'])) {
                $user->userLogout();
                $text = "OK";
            } else {
                $text = "NO OK";
            }
            break;
        case 'cerrarSesion':
            $user->userLogout();

            $text = 'OK';
            break;
    }
}
