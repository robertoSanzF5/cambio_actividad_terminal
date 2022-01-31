<?php

/**
 * Class User
 */
class User
{
    /** @var string */
    private $username, $nombre, $correo, $token, $hashToken, $cryptMethod, $state, $intentos, $maxIntentos;
    /** @var int */
    private $userId;
    /** @var Centro */
    private $centroActual;

    const BLOQUED = 0;
    const LOGED = 1;
    const WRONG = 2;
    const NEED_UPDATE = 3;
    const CYPHER_METHOD = 'AES256';

// CONSTRUCTORES
    public function __construct()
    {
        $this->cryptMethod = CONFIG['method_encrypt'];
        $this->maxIntentos = 5;
    }

    /**
     * @param int $id
     * @return User|null
     */
    public static function getUserById($id) {
        $user = new self();

        $resul = ConexDB::getDb()->selectOneRowFrom(
            'usuarios',
            ['usuario', 'nombre', 'correo', 'token', 'ultima_modificacion'],
            'user_id = $1',
            [$id]
        );

        if ($resul != null) {
            $user->userId = $id;
            $user->username = $resul['usuario'];
            $user->nombre = $resul['nombre'];
            $user->correo = $resul['correo'];
            $user->hashToken = $resul['token'];
            $user->state = (empty($resul['ultima_modificacion']))?self::NEED_UPDATE:self::LOGED;
        } else {
            $user = null;
        }

        return $user;
    }

    /**
     * @param $username
     * @param $nombre
     * @param $correo
     * @return User
     */
    public static function createUser($username, $nombre, $correo)
    {
        $user = new self();

        $user->username = $username;
        $user->nombre = $nombre;
        $user->correo = $correo;

        return $user;
    }

    /**
     * @param $userId
     * @param $token
     * @return User
     */
    public static function checkUserLoged($userId, $token) {
        $user = null;

        if (!empty($userId) && !empty($token) && isset($_SESSION['userId']) && $userId == $_SESSION['userId']) {
            $user = self::getUserById($userId);

            if ($user != null && !$user->checkToken($token)) {
                $user = null;
            }
        }

        return $user;
    }

    /**
     * @param string $usuario
     * @param string $password
     * @return User
     * @throws Exception
     */
    public static function userLogin($usuario, $password) {
        $db = ConexDB::getDb();

        $user = new self();
        $user->username = $usuario;

        $resul = $db->selectOneRowFrom(
            'usuarios',
            ['nombre', 'correo', 'user_id', 'ultimo_centro', 'ultima_modificacion', 'password', 'intentos'],
            'usuario = $1',
            [$usuario]
        );

        if ($resul == null) {
            $user->state = self::WRONG;
        } else {
            $user->correo = $resul['correo'];
            $user->nombre = $resul['nombre'];
            $user->userId = $resul['user_id'];
            $user->centroActual = Centro::getCentro($resul['ultimo_centro']);
            $user->intentos = $resul['intentos'];

            $fecha_modificacion = $resul['ultima_modificacion'];

            if (!empty($fecha_modificacion)) {
                $password = $user->encript($password);
            } else {
                $db->update(
                    'usuarios',
                    [
                        //'intentos' => $user->intentos,
                        'ultimo_error_login' => date('Y-m-d H:i:s')
                    ],
                    ['user_id' => $user->userId]
                );
            }

            if ($password == $resul['password']) {
                $user->token = $user->generateToken();

                $_SESSION['userId'] = $user->userId;

                if (empty($fecha_modificacion)) {
                    $user->state = self::NEED_UPDATE;
                } else {
                    $user->state = self::LOGED;

                    $user->intentos = 0;
                    $db->update(
                        'usuarios',
                        [
                            'intentos' => $user->intentos,
                            'ultimo_login' => date('Y-m-d H:i:s')
                        ],
                        ['user_id' => $user->userId]
                    );
                }
            } else {
                $user->state = self::WRONG;

                if (!empty($fecha_modificacion)) {
                    $user->intentos++;
                    $db->update(
                        'usuarios',
                        [
                            'intentos' => $user->intentos,
                            'ultimo_error_login' => date('Y-m-d H:i:s')
                        ],
                        ['user_id' => $user->userId]
                    );
                }
            }
        }

        return $user;
    }

// GETS Y SETS
    /**
     * Devuelve el usuario actual de la aplicación.
     * @return self|null
     */
    public static function getCurrentUser() {
        return $GLOBALS['user'];
    }

    /** @return string */
    public function getUsername()
    {
        return trim($this->username);
    }

    /** @return string */
    public function getNombre()
    {
        return $this->nombre;
    }

    /** @return string */
    public function getCorreo()
    {
        return $this->correo;
    }

    /** @return int */
    public function getUserId()
    {
        return $this->userId;
    }

    /** @return string */
    public function getToken()
    {
        return $this->token;
    }

    public function getCentroActual()
    {
        $centro = null;

        if (isset($this->centroActual) && !empty($this->centroActual)) {
            $centro = $this->centroActual;
        } else {
            $centro = Centro::getCentro(ConexDB::getDb()->selectOneField(
                'ultimo_centro',
                'usuarios',
                'user_id = $1',
                [$this->userId]
            ));
        }

        //VALIDAMOS QUE SEA UN CENTRO VÁLIDO PARA ESTE USUARIO
        $this->centroActual = null;

        if ($centro != null) {
            foreach ($this->getAllCentros() as $c) {
                if ($c->getId() == $centro->getId()) {
                    $this->centroActual = $centro;
                    break;
                }
            }
        }

        return $this->centroActual;
    }

    public function getState()
    {
        return $this->state;
    }

// FUNCIONES TOKEN
    /**
     * Borra el token del usuario de la base de datos
     */
    private function deleteToken()
    {
        ConexDB::getDb()->update('usuarios', ['token' => ''], ['user_id' => $this->userId]);
    }

    /**
     * Compara el token proporcionado con el de la base de datos.
     *
     * @param string $token
     * @return boolean
     */
    public function checkToken($token) {
        $this->token = $token;
        return true;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function generateToken() {
        $length = 10;
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $string = '';

        for ($x = 0; $x < $length; $x++) {
            $string .= substr($chars, rand(0, strlen($chars)), 1);
        }

        $this->hashToken = $this->encript($string);

        ConexDB::getDb()->update(
            "usuarios",
            ['token' => $this->hashToken],
            ['user_id' => $this->userId]
        );

        return $string;
    }

    /**
     * @param string $string
     * @return string
     */
    public function encript($string) {
        return hash($this->cryptMethod, $string);
    }

// FUNCIONES USUARIOS
    /** @return array */
    public function getEntornoInfo() {
        $nivel_autorizacion = $this->getAutorizacionUsuario();

        $listaCentros = 'Seleccione Centro';
        $values = '--';
        $centros = '';
        $ceco = null;
        $centroId = '--';
        $nombre_db = '';
        $centro_actual = $this->getCentroActual();

        if ($this->getCentroActual() != null) {
            $ceco = $centroId = $this->centroActual->getId();
            $nombre_db = $this->centroActual->getDescripcion();
        }

        foreach ($this->getAllCentros() as $centro) {
            $listaCentros .= ', '. $centro->getDescripcion();
            $values .= ','. $centro->getId();

            if (!empty($centros)) {
                $centros .= ',';
            }

            $centros .= $centro->getId();
        }

        return [
            'username' => $this->nombre,
            'usuario' => $this->userId,
            'cuantasbd' => count($this->getAllCentros()),
            'nivel' => $nivel_autorizacion,
            'ceco' => $ceco,
            'nombre_db'=> $nombre_db,
            'url' => CONFIG['url'],
            'centro' => $centroId,
            'val' => $values,
            'token' => $this->token,
            'des' => $listaCentros,
            'centros' => $centros
        ];
    }

    public function getAutorizacionUsuario() {
        return ConexDB::getDb()->selectOneField(
            'nivel_autorizacion',
            'usuarios',
            'user_id = $1',
            [$this->userId]
        );
    }

    /**
     * Borramos el token del usuario.
     */
    public function userLogout() {
        $this->deleteToken();
        session_unset();
        session_destroy();

        $this->__destruct();
    }

    /**
     * @param string $newPassword
     * @return bool
     */
    public function changePassword($newPassword) {
        return ConexDB::getDb()->update(
            "usuarios",
            [
                'password' => $this->encript($newPassword),
                'ultima_modificacion' => date('Y-m-d H:i:s')
            ],
            ['user_id' => $this->userId]
        );
    }

    /**
     * @param int $centro
     * @return bool
     * @throws Exception
     */
    public function setCentro($centro) {
        $c = Centro::getCentro($centro);

        if ($c != null) {
            $update = ConexDB::getDb()->update(
                "usuarios",
                ['ultimo_centro' => $centro],
                ['user_id' => $this->userId]
            );

            if ($update) {
                $this->centroActual = $c;
            }

            return $update;
        }

        return false;
    }

    public function addCentro($centro) {
        $db = ConexDB::getDb();

        //COMPROBAMOS QUE NO EXISTA YA ESTA RELACIÓN
        $relacion = $db->selectOneRowFrom(
            'usuarios_centro',
            null,
            'user_id = $1 AND centro_trabajo = $2',
            [$this->userId, $centro]
        );

        if ($relacion == null) {
            //COMPROBAMOS QUE EXISTA EL CENTRO
            $c = Centro::getCentro($centro);

            if ($c != null) {
                $db->insertQuery(
                    'usuarios_centro',
                    [
                        'user_id' => $this->userId,
                        'centro_trabajo' => $c->getId()
                    ]
                );
            }
        }
    }

    /**
     * @param $centro_id int
     * @return boolean
     */
    public function hasCentro($centro_id) {
        $total = ConexDB::getDb()->contarRegistros('usuarios_centro', 'user_id = $1 AND centro_trabajo = $2', [$this->getUserId(), $centro_id]);

        return $total;
    }

    /** @return Centro[] */
    public function getAllCentros($centro = false) {
        $centros = [];
        $where = "activo = 'SI'";
        $params= [];

        if(!$centro){
            $where = "centro_trabajo IN (SELECT usuarios_centro.centro_trabajo FROM usuarios_centro WHERE user_id = $1) and activo = 'SI'";
            $params=[$this->userId];
        }
        $consulta = ConexDB::getDb()->selectFrom(
            'centros',
            ['centro_trabajo', 'descripcion'],
            $where,
            $params,
            null,
            'centro_trabajo'
        );

        if (!empty($consulta)) {
            foreach ($consulta as $c) {
                $centro = new Centro($c['centro_trabajo'], $c['descripcion']);

                $centros[] = $centro;
            }
        }

        return $centros;
    }

    public function guardar() {
        $db = ConexDB::getDb();
        $campos = [
            'usuario' => $this->username,
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'intentos' => 0,
            'ultima_modificacion' => date('Y-m-d H:i:s'),
        ];

        if (isset($this->userId)) {
            $db->update('usuarios', $campos, 'user_id = $1', $this->userId);
        } else {
            $db->insertQuery('usuarios', $campos);
        }
    }

    public function __destruct()
    {
        unset($username, $nombre, $correo, $token, $hashToken, $cryptMethod, $state, $intentos, $maxIntentos, $userId, $centroActual);
    }
}