<?php
function cargar_datos($trabajador){

}
function obtener_datos_trabajador($cod_trabajador){
    $db = ConexDB::getDb();

    $query = "select trabajador ||' '||nombre ||' '|| apellidos as nombre_completo from trabajadores where trabajador = $1";
    $result = $db->selectQuery($query, [$cod_trabajador]);
    if(!is_null($result)){
        foreach ($result as $valor){
            $nombre_completo = $valor['nombre_completo'];
        }
    }else{
        $nombre_completo = null;
    }
    return $nombre_completo;
}
function obtener_grupo_actividad($trabajador){
    $db = ConexDB::getDb();
    $hoy = date('Y-m-d');

    $query ="select a.descripcion actividad, ga.descripcion grupo from historico h
    inner join actividades a on a.actividad = h.actividad and a.grupoactividad = h.grupoactividad and a.centro = h.centro_trabajo
    inner join grupos_actividad ga on ga.centro = h.centro_trabajo and ga.grupo_actividad = h.grupoactividad
where fjornada = $1 and trabajador = $2 and h.grupoactividad != $3 and mfin is null";

    $result = $db->selectQuery($query, [$hoy, $trabajador, 99]);

    if(!is_null($result)){
        foreach ($result as $valor){
           $actividad = $valor['actividad'];
            $grupo = $valor['grupo'];
            $grupo_act['actividad'] = $actividad;
            $grupo_act['grupo'] = $grupo;
        }
    }else{
        $grupo_act = null;
    }
    return $grupo_act;
}
function getCentro($trabajador){
    $db = ConexDB::getDb();

    $query = "select centro_trabajo from trabajadores where trabajador = $1";
    $result = $db->selectQuery($query, [$trabajador]);
    foreach ($result as $valor){
        $centro = $valor['centro_trabajo'];
    }

    return $centro;
}
function getGruposCentro($centro, $all = false) {
    $db = ConexDB::getDb();

    if ($centro instanceof Centro) {
        $centro = $centro->getId();
    }

    if (strlen($centro) == 1) {
        $centro = '0' . $centro;
    }

    $where = "centro = $1";
    $params = [$centro];

    if (!$all) {
        $where .= " AND swactivo = $2";
        $params[] = 'ON';
    }

    return $db->selectFrom(
        'grupos_actividad',
        ['logo', 'grupo_actividad', 'descripcion', 'swactivo'],
        $where,
        $params,
        null,
        'swactivo desc, grupo_actividad'
    );
}
function getActividadesGrupo($grupo, $centro = null, $activos = true)
{
    $db = ConexDB::getDb();

    $where = 'grupoactividad = $1';
    $params = [$grupo];

    if ($activos) {
        $params[] = 'ON';
        $where .= " AND swactivo = $" . count($params);
    }

    if (!is_null($centro)) {
        if ($centro instanceof Centro) {
            $centro = $centro->getId();
        }

        $params[] = $centro;
        $where .= ' AND centro = $' . count($params);

    }

    return $db->selectFrom(
        'actividades',
        ['logo', 'actividad', 'descripcion', 'swactivo'],
        $where,
        $params,
        null,
        'descripcion'
    );
}
function buscar_actividad_abierta($trabajador, $hoy){
    $db = ConexDB::getDb();
    $hora = date('H:i:s');

    if($hora > '00:00:00' && $hora < '05-59-59'){
        $hoy =date("Y-m-d",strtotime($hoy."- 1 days"));;
    }


    $query ="select movimiento from historico 
    where fjornada = $1 and trabajador = $2 and grupoactividad != $3 and mfin is null";

    $result = $db->selectQuery($query, [$hoy, $trabajador, 99]);

    if(!is_null($result)){
        foreach ($result as $valor){
            $movimiento = $valor['movimiento'];
        }
    }else{
        $movimiento = null;
    }
    return $movimiento;
}