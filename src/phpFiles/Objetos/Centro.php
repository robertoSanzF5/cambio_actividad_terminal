<?php

/**
 * Class Centro
 */
class Centro
{
    /** @var int */
    private $id, $mins_media_hora, $mins_hora, $redondeo_entrada_antes,
        $redondeo_entrada_despues;
    /** @var string */
    private $descripcion, $tornos, $hora_dia, $hora_noche, $hora_cambio_jornada;

    /** @var float */
    private $ratio_horas_compensadas;

    /** @var bool */
    private $sabado_festivo, $domingo_festivo;

//CONSTRUCTORES
    public function __construct($id, $descripcion = null, $tornos = null)
    {
        $this->id = intval($id);

        if (!empty($descripcion)) {
            $this->descripcion = $descripcion;
        }

        if (!empty($tornos)) {
            $this->tornos = $tornos;
        }
    }

    /**
     * @return Centro[]
     */
    public static function getAllCentros()
    {
        $centros = [];
        $query = ConexDB::getDb()->selectFrom('centros', null, "activo = 'SI'");

        if (!empty($query)) {
            foreach ($query as $q) {
                $centros[] = new self($q['centro_trabajo'], $q['descripcion'], $q['tornos']);
            }
        }
        return $centros;
    }

    /**
     * @param int $id
     * @return Centro|null
     */
    public static function getCentro($id)
    {
        $centro = null;
        $resul = ConexDB::getDb()->selectOneRowFrom(
            'centros',
            ['descripcion', 'tornos'],
            'centro_trabajo = $1',
            [$id]
        );

        if (!empty($resul)) {
            $centro = new self($id, $resul['descripcion'], $resul['tornos']);
        }

        return $centro;
    }

//GETTERS Y SETTERS
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescripcion()
    {
        if (!isset($this->descripcion)) {
            $this->descripcion = ConexDB::getDb()->selectOneField(
                'descripcion',
                'centros',
                'centro_trabajo = $1',
                [$this->id]
            );
        }

        return $this->descripcion;
    }

    /**
     * @return string
     */
    public function getTorno()
    {
        if (!isset($this->tornos)) {
            $this->tornos = ConexDB::getDb()->selectOneField(
                'tornos',
                'centros',
                'centro_trabajo = $1',
                [$this->id]
            );
        }

        return $this->tornos;
    }

    /**
     * @return bool
     */
    public function hasTornos()
    {
        if (!isset($this->tornos) || empty($this->tornos)) {
            $this->tornos = ConexDB::getDb()->selectOneField(
                "tornos",
                "centros",
                "centro_trabajo = $1",
                [$this->id]
            );
        }

        return $this->tornos == "SI";
    }

    /**
     * @return bool
     */
    public function isSabadoFestivo()
    {
        if (!isset($this->sabado_festivo)) {
            $this->defineContexto();
        }
        return $this->sabado_festivo;
    }

    /**
     * @return bool
     */
    public function isDomingoFestivo()
    {
        if (!isset($this->domingo_festivo)) {
            $this->defineContexto();
        }
        return $this->domingo_festivo;
    }

    /**
     * @return mixed
     */
    public function getHoraDia()
    {
        if (!isset($this->hora_dia)) {
            $this->defineContexto();
        }
        return $this->hora_dia;
    }

    /**
     * @return mixed
     */
    public function getHoraNoche()
    {
        if (!isset($this->hora_noche)) {
            $this->defineContexto();
        }
        return $this->hora_noche;
    }

    /**
     * @return int
     */
    public function getMinutosParaMediaHora()
    {
        if (!isset($this->mins_media_hora)) {
            $this->defineContexto();
        }
        return $this->mins_media_hora;
    }

    /**
     * @return int
     */
    public function getMinutosParaUnaHora()
    {
        if (!isset($this->mins_hora)) {
            $this->defineContexto();
        }
        return $this->mins_hora;
    }

    /**
     * @return int
     */
    public function getRedondeoEntradaAntes()
    {
        if (!isset($this->redondeo_entrada_antes)) {
            $this->defineContexto();
        }
        return $this->redondeo_entrada_antes;
    }

    /**
     * @return int
     */
    public function getRedondeoEntradaDespues()
    {
        if (!isset($this->redondeo_entrada_despues)) {
            $this->defineContexto();
        }
        return $this->redondeo_entrada_despues;
    }

    /**
     * @return string
     */
    public function getHoraCambioJornada()
    {
        if (!isset($this->hora_cambio_jornada)) {
            $this->defineContexto();
        }
        return $this->hora_cambio_jornada;
    }

    /**
     * @return float
     */
    public function getRatioHorasCompensadas()
    {
        if (!isset($this->ratio_horas_compensadas)) {
            $this->defineContexto();
        }
        return $this->ratio_horas_compensadas;
    }

    /**
     * @return array
     */
    public function getGrupos()
    {
        return ConexDB::getDb()->selectFrom(
            'grupos_actividad',
            ['grupo_actividad', 'descripcion', 'swactivo', 'logo'],
            'centro = $1',
            [$this->id],
            [],
            'grupo_actividad'
        );
    }

    /**
     * @return array
     */
    public function getEmpleados()
    {
        return ConexDB::getDb()->selectFrom(
            'trabajadores',
            null,
            'centro_trabajo = $1 AND (fbaja IS NULL OR fbaja >= $2)',
            [$this->id, date('Y-m-d')],
            [],
            'trabajador ASC'
        );
    }

    /**
     * @param string $fecha
     * @return array
     */
    public function getEmpleadosActivos($fecha = null)
    {
        if (empty($fecha)) {
            $fecha = Date('Y-m-d');
        }

        return ConexDB::getDb()->selectFrom(
            'trabajador',
            null,
            'centro_trabajo = $1 AND falta <= $2 AND (fbaja IS NULL OR fbaja > $2)',
            [$this->id, $fecha]
        );
    }


//FUNCIONES

    /**
     * Recoge el contexto de la base de datos
     * y asigna los valores a las variables correspondientes
     */
    private function defineContexto()
    {
        $q = ConexDB::getDb()->selectOneRowFrom(
            'contexto',
            null,
            'centro = $1',
            [$this->id]
        );

        if (!empty($q)) {
            $this->sabado_festivo = $q['festivo_sabado'] === 'S';
            $this->domingo_festivo = $q['festivo_domingo'] === 'S';
            $this->hora_dia = $q['hora_dia'];
            $this->hora_noche = $q['hora_noche'];
            $this->mins_media_hora = $q['minutos_para_media_hora'];
            $this->mins_hora = $q['minutos_para_una_hora'];
            $this->redondeo_entrada_antes = $q['redondeo_entrada_antes_min'];
            $this->redondeo_entrada_despues = $q['redondeo_entrada_despues_min'];
            $this->hora_cambio_jornada = $q['hora_cambio_jornada'];
            $this->ratio_horas_compensadas = $q['ratio_horas_compensadas'];
        }
    }

    public function ajustarTiempo($tiempo, $resto = 0)
    {
        $resultado = intval($tiempo / 60);
        $resto += $tiempo % 60;

        if ($resto >= $this->getMinutosParaUnaHora()) {
            $resultado++;
            $resto -= 60;
        } elseif ($resto >= $this->getMinutosParaMediaHora()) {
            $resultado += 0.5;
            $resto -= $this->mins_media_hora;
        }

        return [
            'resultado' => $resultado,
            'resto' => $resto
        ];
    }

    public function ajustarHoras($noche, $dia, $fest_noche, $fest_dia)
    {
        $t_noche = $this->ajustarTiempo($noche);
        $t_dia = $this->ajustarTiempo($dia, $t_noche['resto']);
        $tf_noche = $this->ajustarTiempo($fest_noche);
        $tf_dia = $this->ajustarTiempo($fest_dia, $tf_noche['resto']);

        return [
            'noche' => $t_noche['resultado'],
            'dia' => $t_dia['resultado'],
            'festivo_noche' => $tf_noche['resultado'],
            'festivo_dia' => $tf_dia['resultado']
        ];
    }
}