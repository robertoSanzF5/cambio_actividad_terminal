<?php

/**
 * @property string $host
 * @property string $db
 * @property string $user
 * @property string $password
 * @property string $timeout
 * @property null|resource $conn
 */
class ConexDB
{
    /**
     * @var string
     */
    private $host, $db, $user, $password, $timeout;
    /**
     * @var DateTime
     */
    private $timeBegin, $timeEnd = null;
    /**
     * @var resource
     */
    private $conn = null;
    /**
     * @var bool
     */
    private $debug = false;
    private $transaction = false;
    private $last_resource = null;

    //CONSTRUCTORES Y DESTRUCTORES
    /**
     * Crea la conexión a la base de datos al crear el objeto.
     */
    public function __construct()
    {
        $this->connect();
        $this->debug = CONFIG['debug']['query'];
    }

    /**
     * Cierra la conexión al destruir el objeto.
     */
    public function __destruct()
    {
        if ($this->conn != null) {
            if ($this->transaction) {
                $this->rollbackTransaction();
                $this->execQuery("ROLLBACK");
            }

            pg_close($this->conn);
        }
    }

    /**
     * Recupera conexión general con la base de datos.
     *
     * @return self|null
     */
    public static function getDb() {
        global $postgres_conexion;

        if (!isset($postgres_conexion) || $postgres_conexion == null || pg_connection_status($postgres_conexion->getConnection()) != PGSQL_CONNECTION_OK) {
            $postgres_conexion = new self();
        }

        return $postgres_conexion;
    }

    // FUNCIONES DE GESTIÓN DE LA CONEXIÓN

    /**
     * Establece conexión con la base de datos.
     *
     * @return bool
     */
    public function connect()
    {
        $config = CONFIG['db'];

        $this->host = $config['host'];
        $this->db = $config['database'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->timeout = $config['timeout'];

        $connect_string = "host=" . $this->host . " dbname=" . $this->db . " user=" . $this->user . " password=" . $this->password . " connect_timeout=" . $this->timeout;

        $conex = pg_connect($connect_string, PGSQL_CONNECT_FORCE_NEW);

        if ($conex !== false) {
            $this->conn = $conex;

            pg_query($conex, "SET TIMEZONE='Europe/Madrid'");
        }

        return $this->getConnectionStatus();
    }

    /**
     * Desconecta de la base de datos si se está conectado.
     */
    public function disconnect(){
        if ($this->conn != null) {
            pg_close($this->conn);
        }
    }

    /**
     * Devuelve la conexión con la base de datos. Si no hay ninguna establecida la crea.
     *
     * @return resource|null;
     */
    public function getConnection()
    {
        if (is_null($this->conn)) {
            $this->connect();
        }

        return $this->conn;
    }

    /**
     * Devuelve el estado de la conexión
     *
     * @return boolean
     */
    public function getConnectionStatus()
    {
        if ($this->conn != null && pg_connection_status($this->conn) == PGSQL_CONNECTION_OK) {
            return true;
        }

        return false;
    }

    /**
     * Comprueba si existe la tabla indicada en la base de datos.
     *
     * @param string $tabla
     * @return bool
     */
    public function checkTableExist($tabla = '') {
        $query = $this->selectQuery(
            'SELECT to_regclass($1) tabla',
            [$tabla]
        );

        return (!empty($query)) && (!empty($query[0])) && ($query[0]['tabla'] == $tabla);
    }

    //FUNCIONES DE CONSULTAS

    /**
     * Ejecuta una consulta y devuelve el resultado de esta.
     *
     * @param $queryStr     String
     * @param $params       array
     * @param $debug        bool
     * @return false|resource
     * @throws Exception
     */
    public function execQuery($queryStr, $params = [], $debug = false) {
        if ($debug || $this->debug) {
            $this->timeBegin = new DateTime();
        }

        if (is_null($params)) {
            $params = [];
        }

        $this->last_resource = $query = pg_query_params($this->conn, $queryStr, $params);

        if ($debug || ($debug && $this->debug)) {
            $this->timeEnd = new DateTime();
        }

        return $query;
    }

    /**
     * Ejecuta una consulta select en la base de datos y devuelve los resultados como Array.
     *
     * @param string    $queryStr
     * @param array     $params
     * @param boolean   $debug
     * @return array|null
     * @throws Exception
     */
    public function selectQuery($queryStr, $params = [], $debug = false) {
        $fetch = null;
        $query = $this->execQuery($queryStr, $params, $debug);

        if ($query !== false) {
            $fetch = pg_fetch_all($query);
            if ($fetch === false) {
                $fetch = null;
            }
        }

        if ($debug || ($debug && $this->debug)) {
            $this->logQuery($queryStr, $params, count($fetch));
        }

        return $fetch;
    }

    /**
     * @param string $table
     * @param null|array|string $fields
     * @param string $where
     * @param array $params
     * @param array $joins          array('$tabla' => array('on' => array('t1.field1' => 't2.field2', ...), ['tipo' => 'INNER'|'LEFT'|'RIGHT'|...]), ...)
     * @param string|array $order   ['campo' => 'ASC o DESC']
     * @param int $limit
     * @param bool $debug
     * @return array|null
     * @throws Exception
     */
    public function selectFrom($table, $fields = null, $where = null, $params = [], $joins = [], $order = null, $limit = 0, $debug = false) {
        $queryStr = "SELECT ";

        if (empty($fields)) {
            $queryStr .= '*';
        } else {
            if (gettype($fields) == 'string') {
                $queryStr .= $fields;
            } elseif (gettype($fields) == 'array') {
                $queryStr .= implode(", ", $fields);
            }
        }

        $queryStr .= " FROM " . $table;

        if (!empty($joins)) {
            foreach ($joins as $table => $opts) {
                $on = null;
                if (!isset($opts['on']) && !isset($opts['ON'])) {
                    throw new Exception('Faltan condiciones de JOIN');
                }

                $queryStr .= " " . ((isset($opts['tipo']) && !empty($opts['tipo']))?$opts['tipo']:"INNER") . " JOIN " . $table;
                $onJoin = "";

                $on = isset($opts['on'])?$opts['on']:$opts['ON'];

                foreach ($on as $a => $b) {
                    if (!empty($onJoin)) {
                        $onJoin .= " AND ";
                    }

                    $onJoin .= $a . " = " . $b;
                }

                $queryStr .= " ON " . $onJoin;
            }
        }

        if (!empty($where)) {
            $queryStr .= " WHERE " . $where;
        }

        if (!empty($order)) {
            $queryStr .= ' ORDER BY ';

            if (gettype($order) == 'string') {
                $queryStr .= $order;
            } elseif (gettype($order) == 'array') {
                foreach ($order as $campo => $valor) {
                    $queryStr .= $campo . ' ' . $valor . ',';
                }

                $queryStr = substr($queryStr, 0, -1);
            }
        }

        if ($limit > 0) {
            $queryStr .= " LIMIT " . $limit;
        }

        return $this->selectQuery($queryStr, $params, $debug);
    }

    /**
     * @param string            $table
     * @param null|array|string $fields
     * @param string            $where
     * @param array             $params
     * @param array             $joins      array('$tabla' => array('on' => array('t1.field1' => 't2.field2', ...), ['tipo' => 'INNER'|'LEFT'|'RIGHT'|...]), ...)
     * @param string|array      $order
     * @param bool              $debug
     * @return array|null
     * @throws Exception
     */
    public function selectOneRowFrom($table, $fields = null, $where = null, $params = [], $joins = [], $order = null, $debug = false) {
        $query = $this->selectFrom($table, $fields, $where, $params, $joins, $order, 1, $debug);

        if (!empty($query)) {
            return $query[0];
        } else {
            return null;
        }
    }

    /**
     * @param string|array $field   nombre campo o array('alias' => 'campo')
     * @param string $table
     * @param string $where
     * @param array $params
     * @param array $joins          array('$tabla' => array('on' => array('t1.field1' => 't2.field2', ...), ['tipo' => 'INNER'|'LEFT'|'RIGHT'|...]), ...)
     * @param string|array $order
     * @param bool $debug
     * @return mixed|null
     * @throws Exception
     */
    public function selectOneField($field, $table, $where = null, $params = [], $joins = [], $order = null, $debug = false) {
        $campo = '';
        $alias = '';
        if (gettype($field) == 'array') {
            foreach ($field as $alias => $campo) {
                $field = $campo . ' ' . $alias;
            }
        }

        $query = $this->selectOneRowFrom($table, $field, $where, $params, $joins, $order, $debug);

        if ($query != null) {
            if ($alias == '') {
                return $query[$field];
            } else {
                return $query[$alias];
            }
        } else {
            return null;
        }
    }

    /**
     * @param   string      $table
     * @param   string      $where
     * @param   array       $params
     * @param   array       $joins      array('$tabla' => array('on' => array('t1.field1' => 't2.field2', ...), ['tipo' => 'INNER'|'LEFT'|'RIGHT'|...]), ...)
     * @param   boolean     $debug
     * @return  int
     * @throws  Exception
     */
    public function contarRegistros($table, $where = null, $params = [], $joins = [], $debug = false) {
        return intval($this->selectOneRowFrom($table, 'COUNT(*) cuenta', $where, $params, $joins, $debug)['cuenta']);
    }

    //FUNCIONES DE ACTUALIZACIÓN DE DATOS

    /**
     * @param   string  $table
     * @param   array   $datos  Array asociativo de los datos a insertar.<br/><i>nombre_campo => valor</i>
     * @param   bool    $debug
     * @return bool
     * @throws Exception
     */
    public function insertQuery($table, $datos, $debug = false) {
        if ($debug || $this->debug) {
            $this->timeBegin = new DateTime();
        }

        $resul = pg_insert($this->conn, $table, $datos);

        if ($debug || ($debug && $this->debug)) {
            $this->timeEnd = new DateTime();
        }

        return $resul !== false;
    }

    /**
     * @param $table    String      nombre
     * @param $datos    array       array("nombre_campo" => "valor", ...)
     * @param $debug    boolean
     *
     * @return boolean
     */
    public function insertSql($table, $datos, $debug = false) {
        $fields = [];
        $values = [];

        foreach ($datos as $f => $v) {
            $fields[] = $f;
            $values[] = is_null($v)?'null':"'$v'";
        }

        $sql = "INSERT INTO $table (" . implode(',', $fields) . ") VALUES (" . implode(',', $values) . ")";

        $query = $this->execQuery($sql);

        return $query !== false && pg_affected_rows($query) > 0;
    }

    /**
     * INSERCIÓN DE MÚLTIPLES FILAS DE MANERA SIMULTANEA
     *
     * @param $table    String
     * @param $fields   Array
     * @param $datos    Array   Array de Arrays asociativo ["Campo" => "VALOR"]
     * @param $limit    Integer Números de filas que se deben insertar en cada query
     *
     * @return boolean
     */
    public function multiInsert($table, $fields, $datos, $limit = 0) {
        $resul = false;
        $sql = "INSERT INTO " . $table;

        if (!empty($fields)) {
            $sql .= " (" . implode(",", $fields) . ")";
        }

        $sql .= " VALUES ";
        $rows = 0;
        $datas = '';

        if ($limit == 0) {
            //MÁXIMO DE FILAS POR INSERT POR DEFECTO
            $limit = 10000;
        }

        foreach ($datos as $values) {
            $rows++;
            $fields = '';
            foreach ($values as $v) {
                if (is_null($v)) {
                    $v = 'null';
                }
                $fields .= $v . ',';
            }
            $datas .= '(' . substr($fields, 0, -1) . '),';

            //SI SE LLEGA AL LÍMITE ESTABLECIDO DE FILAS, SE EJECUTA EL INSERT
            if ($rows >= $limit) {
                $tempSql = $sql . substr($datas, 0, -1);
                $datas = '';

                $q = $this->execQuery($tempSql);
                if ($q === false) {
                    echo $tempSql . PHP_EOL;
                    return false;
                }

              //  echo "INSERTADOS " . $limit . " REGISTROS" . PHP_EOL;
                $resul = true;
                $rows = 0;
                unset($tempSql);
            }
        }

        if ($datas != '') {
            $sql .= substr($datas, 0, -1);
            $resul = $this->execQuery($sql) !== false;

            if ($resul) {
               // echo "INSERTADOS " . $rows . " REGISTROS RESTANTES." . PHP_EOL;
            } else {
               // echo $sql . PHP_EOL;
            }
        }

        return $resul;
    }

    /**
     * Ejecuta una consulta de actualización de datos en la BBDD
     *
     * @param   string  $table
     * @param   array   $datos      Array asociativo de los datos a actualizar.<br/>
     *                              <i>nombre_campo => valor</i>
     * @param   array   $conditions Array asociativo de los campos que sirven para filtrar los valores.<br/>
     *                              <i>nombre_campo => valor</i>
     * @param   bool    $debug
     * @return bool
     * @throws Exception
     */
    public function update($table, $datos, $conditions = [], $debug = false) {
        if ($debug || $this->debug) {
            $this->timeBegin = new DateTime();
        }

        $resul = pg_update($this->conn, $table, $datos, $conditions);

        if ($debug || ($debug && $this->debug)) {
            $this->timeEnd = new DateTime();
        }

        return $resul;
    }

    /**
     * Prepara y lanza una consulta de actualización con los datos proporcionados
     * devolviendo los campos indicados en <b>returning</b> o en su defecto
     * <b>true</b> si se ejecuta exitosamente o
     * <b>false</b> si hay un error o no actualiza ninguna fila.
     *
     * @param String    $table
     * @param array     $datosSet
     * @param String    $where
     * @param array     $params
     * @param String    $returning
     * @return array|resource
     * @throws Exception
     */
    public function updateQuery($table, $datosSet, $where = null, $params = [], $returning = null) {
        $return_val = false;
        if (empty($table) || empty($datosSet)) {
            throw new Exception("Datos obligatorios vacios.");
        }

        $sets = [];

        foreach ($datosSet as $f => $v) {
            $sets[] = " $f = $v";
        }
        $queryStr = "UPDATE $table SET " . implode(', ', $sets);

        if (!empty($where)) {
            $queryStr .= " WHERE $where";
        }

        if (!empty($returning)) {
            $queryStr .= " RETURNING $returning";
        }

        $resul = $this->execQuery($queryStr, $params);

        if ($resul !== false) {
            if (empty($returning)) {
                $return_val = pg_affected_rows($resul) > 0;
            } else {
                if (pg_affected_rows($resul) > 0) {
                    $return_val = pg_fetch_all($resul);
                } else {
                    $return_val = null;
                }
            }
        }

        return $return_val;
    }

    /**
     * @param   string  $table
     * @param   array   $conditions
     * @param   bool    $debug
     * @return bool
     * @throws Exception
     */
    public function deleteQuery($table, $conditions = [], $debug = false)
    {
        if ($debug || $this->debug) {
            $this->timeBegin = new DateTime();
        }

        $resul = pg_delete($this->conn, $table, $conditions);

        if ($debug || ($debug && $this->debug)) {
            $this->timeEnd = new DateTime();
        }

        return $resul;
    }

    //FUNCIONES DE TRANSACCIONES
    public function beginTransaction() {
        $this->transaction = true;
        return pg_query($this->conn, "BEGIN") !== false;
    }

    public function commitTransaction() {
        return pg_query($this->conn, "COMMIT") !== false;
    }

    public function rollbackTransaction() {
        return pg_query($this->conn, "ROLLBACK") !== false;
    }

    public function last_error() {
        $error = '';
        if (!is_null($this->last_resource)) {
            $error = trim(pg_last_error($this->last_resource));
        }

        if (empty($error) && !is_null($this->last_resource)) {
            $error = trim(pg_errormessage($this->last_resource));
        }

        if (empty($error)) {
            $error = trim(pg_last_error($this->conn));
        }

        if (empty($error)) {
            $error = trim(pg_errormessage($this->conn));
        }

        return $error;
    }

    //CONTROL DE LOGS
    public function logQuery($query, $params, $totalResults) {
        $text = "Query: " . $query . PHP_EOL;

        if (count($params) > 0) {
            $text .= "Params: " . implode(',', $params) . PHP_EOL;
        }

        $text .= "Resultados: " . $totalResults . PHP_EOL;

        $time = date_diff($this->timeBegin, $this->timeEnd);

        $timeStr = substr('0' . $time->i, -2) . ':' . substr('0' . $time->s, -2);

        $text .= "Tiempo ejecución:" . $timeStr . PHP_EOL;

        registerLog('querys', $text);
    }
}