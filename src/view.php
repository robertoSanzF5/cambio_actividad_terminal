<?php

//DEFINIMOS CONTROLADOR SEGÚN LA VISTA SOLICITADA
if (($user = User::getCurrentUser()) != null) {// && isset($_GET['site'])) {
    $validActions = [
        'resumen_cuadrante', 'actividades_grupo', 'detalle_actividad', 'sel_grupo_dft',
        'edit_jornada', 'nueva_actividad', 'ver_fichajes_evalos', 'control_acceso_reconstruir',
        'sel_grupo', 'sel_actividad', 'ncontrol_acceso', 'costes_por_trabajador'
    ];

    if (isset($_GET['site']) && in_array($_GET['site'], $validActions)) {
        $control = $_GET['categoria'] . '&action=' . $_GET['site'];

        foreach ($_GET as $clave => $valor) {
            if ($clave != 'categoria' && $clave != 'site' && $clave != 'usuario' && $clave != 'token') {
                $control .= '&' . $clave . '=' . $valor;
            }
        }
    } else {
        if ($user != null) {
            if (isset($_SESSION['control']) && !empty($_SESSION['control'])) {
                $control = $_SESSION['control'];
            } else {
                $control = 'menuges01';
            }
        } else {
            header("Location: " . CONFIG['url']);
        }
    }

    $controller = '?control=' . $control . '&usuario=' . $user->getUserId() . '&token=' . $user->getToken();
} else {
    $controller = '?control=login';
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menú de Gestión</title>
    <link href="jscss/profoundui.css?1" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="jscss/commons.css?<?php echo getLastModifyFile("jscss/commons.css"); ?>">
    <script type="text/javascript" src="jscss/runtime.js"></script>
    <script type="text/javascript" src="FusionChartsXT/js/pui-fusioncharts.js"></script>
    <script type="text/javascript" src="jscss/commons.js?<?php echo getLastModifyFile("jscss/commons.js"); ?>"></script>
    <script type="text/javascript">
        var fmt = null;
        var data = [];

        window.onload = function() {
            pui.controller = '<?= $controller; ?>';
            pui.start();
        }
    </script>
</head>
<body>
<div id="pui"></div>
<div id="loading-banner"></div>
</body>
</html>