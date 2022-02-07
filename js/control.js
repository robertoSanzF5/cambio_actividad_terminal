function login (){

        let tarjeta = get('tb_tarjeta');

        let peti = null;
        try{
            peti = ajaxJSON({
                url: '../control.php',
                method: 'post',
                params: {
                    tarjeta: tarjeta,
                    accion: 'login',
                }
            });
        }catch (error){
            alert("Se ha producido un error.\nContacte con soporte.");
        }
        if(peti['data']['validUser']){
            console.log('Logueado');

            pui.show({
                path: pui.view,
                screen: 'ftm01',
                data: {
                    lb_trabajador: peti['data']['trabajador'],
                    lb_actividad_actual: peti['data']['actividad'],
                    lb_grupo_actual: peti['data']['grupo'],
                    trabajador: peti['data']['trabajador'],
                    centro: peti['data']['centro'],
                    actividad: peti['data']['actividad'],
                    grupo: peti['data']['grupo'],
                    cod_trabajador: peti['data']['cod_trabajador']
                }
            });
        }

        if(peti['data']['error']){
            let error = peti['data']['error'];
            console.log(error);
            pui.show({
                path: pui.view,
                screen: 'ftm00',
                data: {
                    errorLogin: error,
                }
            });
            applyProperty('errorLogin', 'visibility', 'visible');

        }


    //cargar_grupo();
}
function salir(){
    //hacer una peticion ajax para hacer un sesion.destroy
    try{
        peti = ajaxJSON({
            url: '../control.php',
            method: 'post',
            params: {
                accion: 'salir',
            }
        });
    }catch (error){
        alert("Se ha producido un error.\nContacte con soporte.");
    }

    pui.show({
        path: pui.view,
        screen: 'ftm00'
    });
}

function cargar_grupo() {
    let choices = 'SELECCIONE UN GRUPO';
    let values = '***';

    try {
        var peti = ajaxJSON({
            url: get("url") + '../control.php',
            params: {
                usuario: get("usuario"),
                token: get("token"),
                accion: 'cargar_grupo'
            },
            method: 'post'
        });
    } catch (error) {
        peti = null;
    }

    if (peti == null) {
        alert("Error al obtener los grupos del servidor.");
    } else {
        let descripcion = peti['data']['tgru'];
        let grupos = peti['data']['vgru'];

       for (let i = 0; i < descripcion.length; i++) {
            choices += ', ' + descripcion[i];
            values += ', ' + grupos[i];
        }
    }
    //applyProperty("tgrupo", "visibility", 'hidden');
    applyProperty("tgrupo", "choices", choices);
    applyProperty("tgrupo", "choice values", values);

    pui.set("tgrupo", '***');
   /* if(get('lb_trabajado') == ''){
        alert('vamos bien');
    }*/
    //cargar_actividad_trabajador();
}
function cargar_actividad(){
    let grupo = get('tgrupo')

    let choices = 'SELECCIONE UN GRUPO';
    let values = '***';

    try {
        var peti = ajaxJSON({
            url: get("url") + '../control.php',
            params: {
                usuario: get("usuario"),
                token: get("token"),
                accion: 'cargar_actividad',
                grupo: grupo
            },
            method: 'post'
        });
    } catch (error) {
        peti = null;
    }

    if (peti == null) {
        alert("Error al obtener las actividades.");
    } else {
        let descripcion = peti['data']['tact'];
        let actividades = peti['data']['vact'];

        for (let i = 0; i < descripcion.length; i++) {
            choices += ', ' + descripcion[i];
            values += ', ' + actividades[i];
        }
    }

    applyProperty("tactividad", "choices", choices);
    applyProperty("tactividad", "choice values", values);

    pui.set("tactividad", '***');

}
function cambiar_actividad(){
    let grupo = get('tgrupo');
    let actividad = get('tactividad');
    let trabajador = get('cod_trabajador');
    let centro = get('centro');

    if(grupo == '***' || actividad == '***'){
        alert('Error!!! Hay que seleccionar un grupo y una actividad');
    }else{
        try {
            var peti = ajaxJSON({
                url: get("url") + '../control.php',
                params: {
                    usuario: get("usuario"),
                    token: get("token"),
                    accion: 'cambiar_grupo_actividad',
                    grupo: grupo,
                    actividad: actividad,
                    trabajador: trabajador,
                    centro: centro
                },
                method: 'post'
            });
        } catch (error) {
            peti = null;
        }
        if(peti['data']['insertado'] == 'ok'){
            console.log('Logueado');

            peti['data']['nombreGrupo']
            pui.show({
                path: pui.view,
                screen: 'ftm01',
                data: {
                    lb_actividad_actual: peti['data']['nombre_actividad'],
                    lb_grupo_actual: peti['data']['nombre_grupo'],
                    trabajador: peti['data']['trabajador'],
                    actividad: peti['data']['actividad'],
                    grupo: peti['data']['grupo'],
                    cod_trabajador: peti['data']['cod_trabajador']
                }
            });
        }

        if(peti['data']['error']){
            let error = peti['data']['error'];
           alert(error);

    }
}

}