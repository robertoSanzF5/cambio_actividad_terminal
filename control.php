<?php
//Creo variable view y le paso el archivo json de la vista
$view='json/cambio_act.json';
//Creo el array data1 donde le iremos pasando todos los valores que cargaremos
$data1=array();
//Le meto el codigo html
/*$contenidoHtml = '<div id="titulo"><h1 id="tituloReuniones">Control y reserva de salas de reuniones</h1></div>
<div id="filtros">
        <div id="opciones">

    <img src="imagenes/back.png" id="anterior" onclick="diaAnterior(),estableceEstadoSala()"/>
    <label id="selecionFecha">Seleccione fecha</label> <div id="divFecha"> <input id="fecha" type="date" onkeydown="return false" onchange="estableceEstadoSala()"></div>
    <img src="imagenes/sig.png" id="siguiente" onclick="diaSiguiente(),estableceEstadoSala()"/>
</div>
    <div id="centros">
        <div id="cabusuario">
            <label id="usuario" class="camposSeleccion">Bienvenido : </label><label id="resultadoUsuario">Roberto</label>
        </div>
        <div id="secCentro">
            <label  id="seleccioneCentro" class="camposSeleccion">Seleccione centro: </label>
            <select id="selecCentros" class="camposSeleccion" onchange="establecerCentroTrabajo()">
                <option selected="true" disabled="disabled">Seleccione el centro</option>
                <option><a href="#" class="botonCentros" id="OficinasCentrales">Oficinas Centrales</a></option>
                <!--<option><a href="#" class="botonCentros" id="Camarma">Camarma</a></option>-->
                <option><a href="#" class="botonCentros" id="Cabanillas1">Cabanillas 1 (TEKA)</a></option>
                <!--<option><a href="#" class="botonCentros" id="Cabanillas2">Cabanillas 2 (DIA/IFA)</a></option>
                <option><a href="#" class="botonCentros" id="Cabanillas3">Cabanillas 3 (PLV)</a></option>
                <option><a href="#" class="botonCentros" id="R2_1_Solan">R2 1 (Solan)</a></option>
                <option><a href="#" class="botonCentros" id="CimValles">CIM del Valles</a></option>
                <option><a href="#" class="botonCentros" id="TransporteDistribucion">Transporte y distribución</a></option>
                <option><a href="#" class="botonCentros" id="TekaAlcala">Teka Alcalá</a></option>-->
                <option><a href="#" class="botonCentros" id="Quer">Quer</a></option>
                <option><a href="#" class="botonCentros" id="CadSur">Cad Sur</a></option>
                <!--<option><a href="#" class="botonCentros" id="LeroyMerlin">Leroy Merlin</a></option>-->
            </select>
        </div>
        <div id="cenSeleccionado">
            <label id="centroSeleccionado" class="camposSeleccion">Centro seleccionado: </label><label id="resulCentroSeleccionado" class="camposSeleccion">Oficinas Centrales</label>
        </div>

    </div>

</div>
<div id="salas" >
    

</div>';*/

//$data1['contenidoHtml'] = $contenidoHtml;
//$data1 ['funcionesJavascript'] = 'js/tablaSalas.js?v'.microtime();
//$data1 ['estilosCss'] = 'css/estilosSala.css?v'.microtime();
//Guardo en la variable array output los valores que quiero que se me carguen, el data, el view y el screen
$output = array('data' => $data1, 'view' => $view, 'screen' => 'ftm01' );
//Imprimo el output con el json_encode
echo  json_encode($output);
