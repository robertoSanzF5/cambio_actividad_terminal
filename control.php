<?php
require_once 'phpfiles/config.php';
require_once 'phpfiles/funciones.php';

$db = ConexDB::getDb();



//Creo variable view y le paso el archivo json de la vista
$view='json/cambio_act.json';
$exjs01 = 'js/control.js';
//Creo el array data1 donde le iremos pasando todos los valores que cargaremos
$data1=array();




if(empty($_SESSION['nombre_completo'])){
  $screen = 'ftm00';
}else{
    $screen = 'ftm01';
}



if(!empty($_SESSION['grupo'] )){
    $grupo = $_SESSION['grupo'];

    $data1['grupo'] = $grupo;


}
if(!empty($_SESSION['actividad'] )){
    $actividad = $_SESSION['actividad'];
    $data1['actividad'] = $actividad;
}
if(!empty($_SESSION['trabajador'] )){
    $trabajador = $_SESSION['trabajador'];
    $data1['trabajador'] = $trabajador;
}
if(!empty($_SESSION['centro'] )){
    $centro = $_SESSION['centro'];
    $data1['centro'] = $centro;
}
if(!empty($_SESSION['cod_trabajador'] )){
    $cod_trabajador = $_SESSION['cod_trabajador'];
    $data1['cod_trabajador'] = $cod_trabajador;
}

if(!empty($_POST['accion'])){
    $accion = $_POST['accion'];

        switch ($accion){
            case 'login':
                //loguearse

                        $tarjeta = $_POST['tarjeta'];
                        $query = "select trabajador, nombre, apellidos, centro_trabajo  from trabajadores where tarjetadecontrol = $1";
                        $result = $db->selectQuery($query, [$tarjeta]);
                        if (!is_null($result)) {
                            foreach ($result as $valor) {
                                $cod_trabajador = $valor['trabajador'];
                                $nombre = $valor['nombre'];
                                $apellidos = $valor['apellidos'];
                                $centro = $valor['centro_trabajo'];
                                $nombre_completo = $cod_trabajador . ' - ' . $nombre . ' ' . $apellidos;
                            }
                        } else {
                            $data1['error'] = 'Error!!! Tarjeta no encontrada';
                            session_destroy();
                        }

                        if(isset($cod_trabajador)){
                            $grupo_act = obtener_grupo_actividad($cod_trabajador);
                            if (is_null($grupo_act)) {
                                session_destroy();
                                $data1['error'] = 'Error!!! Grupo y Actividad no encontrada. Verifica tu fichaje';
                                break;
                            } else {

                                $actividad = $grupo_act['actividad'];
                                $grupo = $grupo_act['grupo'];

                            }


                            $_SESSION['cod_trabajador'] = $cod_trabajador;
                            $_SESSION['nombre'] = $nombre;
                            $_SESSION['apellidos'] = $apellidos;
                            $_SESSION['centro'] = $centro;
                            $_SESSION['nombre_completo'] = $nombre_completo;
                            $_SESSION['grupo'] = $grupo;
                            $_SESSION['actividad'] = $actividad;


                            $data1['validUser'] = true;
                            $data1['trabajador'] = $nombre_completo;
                            $data1['grupo'] = $grupo;
                            $data1['actividad'] = $actividad;
                            $data1['cod_trabajador'] = $cod_trabajador;
                            $data1['centro'] = $centro;
                            $screen = 'ftm01';
                        }


                break;
            case 'salir':
                session_destroy();
                break;
            case 'cargar_grupo':
                $descripcion = array() ;
                $num_grupo =array() ;
                $cod_trabajador = $_SESSION['cod_trabajador'];
                $centro = getCentro($cod_trabajador);
                foreach (getGruposCentro($centro) as $g) {
                    $img = 'images/' . $g['logo'] . '.png';

                    $grupos[] = [
                        'grupo' => $g['grupo_actividad'],
                        'descripcion' => $g['descripcion']
                    ];
                }
                foreach ($grupos as $valor){
                    $des = $valor['descripcion'];
                    $num_gru = $valor['grupo'];

                    $descripcion []= $des;
                    $num_grupo[] = $num_gru;
                }
                $data1['tgru'] = $descripcion;
                $data1['vgru'] = $num_grupo;

                break;
            case 'cargar_actividad':
                $grupo = $_POST['grupo'];
                $descripcion = array() ;
                $num_actividad =array() ;
                $cod_trabajador = $_SESSION['cod_trabajador'];
                $centro = getCentro($cod_trabajador);
                foreach (getActividadesGrupo($grupo, $centro) as $g) {
                    $grupos[] = [
                        'actividad' => $g['actividad'],
                        'descripcion' => $g['descripcion']
                    ];
                }
                foreach ($grupos as $valor){
                    $des = $valor['descripcion'];
                    $num_act = $valor['actividad'];

                    $descripcion []= $des;
                    $num_actividad[] = $num_act;
                }
                $data1['tact'] = $descripcion;
                $data1['vact'] = $num_actividad;

                break;
            case 'cambiar_grupo_actividad':
                $trabajador = $_POST['trabajador'];
                $centro = getCentro($cod_trabajador);
                $grupo = $_POST['grupo'];
                $actividad = $_POST['actividad'];
                $hoy = date('Y-m-d');
                $movimiento = buscar_actividad_abierta($trabajador, $hoy);
                if(!is_null($movimiento)){
                    try{
                        $db= ConexDB::getDb();
                        $momento = date("Y-m-d H:i:s");
                        $db->update('historico', ['mfin'=>$momento], ['movimiento'=>$movimiento]);
                        $hora = date('H:i:s');
                        if($hora > '00:00:00' && $hora < '05-59-59'){
                            $hoy =date("Y-m-d",strtotime($hoy."- 1 days"));
                        }
                        $area = $db->selectOneField(
                            'arealocalizacion',
                            'historico',
                            'trabajador = $1',
                            [$trabajador],
                            null,
                            'movimiento DESC'
                        );
                        $db->insertQuery('historico',
                            [
                                'fjornada'=>$hoy,
                                'trabajador'=>$trabajador,
                                'minicio'=>$momento,
                                'mfin'=> null,
                                'grupoactividad'=>$grupo,
                                'actividad'=>$actividad,
                                'arealocalizacion'=>$area,
                                'inicio_crono' => $momento,
                                'centro_trabajo' =>$centro,
                                'origen'=>'TERM'

                            ]);
                        $query = "select a.descripcion nombre_actividad, ga.descripcion nombre_grupo from grupos_actividad ga 
                    inner join actividades a on ga.centro = a.centro and ga.grupo_actividad = a.grupoactividad
                    where a.actividad = $1 and a.grupoactividad = $2";
                        $result = $db->selectQuery($query, [$actividad, $grupo]);
                        foreach ($result as $valor){
                            $nombre_grupo = $valor['nombre_grupo'];
                            $nombre_actividad = $valor['nombre_actividad'];
                        }
                        $_SESSION['grupo'] = $nombre_grupo;
                        $_SESSION['actividad'] = $nombre_actividad;
                        $_SESSION['centro'] = $centro;
                        $data1['insertado']= 'ok';
                        $data1['nombre_grupo']= $nombre_grupo;
                        $data1['nombre_actividad']= $nombre_actividad;
                        $data1['cod_trabajador']=$trabajador;
                        $data1['actividad']=$nombre_actividad;
                        $data1['grupo'] = $nombre_grupo;
                        $data1['centro'] = $centro;
                    }catch(Exception $e){
                        $data1['error'] = 'Error al insertar el cambio de actividad';
                    }


                    //insertar nueva activida
                }else{
                    $data1['error'] = 'Verificar fichaje';
                }

                break;
        }

}



//Guardo en la variable array output los valores que quiero que se me carguen, el data, el view y el screen
$output = array('data' => $data1, 'view' => $view, 'screen' => $screen );
//Imprimo el output con el json_encode
echo  json_encode($output);
