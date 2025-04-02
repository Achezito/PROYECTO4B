<?php

require_once __DIR__ . '/../config/conection.php';

class User
{
    private $id_user;
    private $name;
    private $email;
    private $id_role;

    // Constructor
    public function __construct($id_user = null, $name = null, $email = null, $id_role = null)
    {
        $this->id_user = $id_user;
        $this->name = $name;
        $this->email = $email;
        $this->id_role = $id_role;
    }

    private static $loginQuery = "
    SELECT
    u.id_user,
    u.name,
    u.password_hash,
    u.email,
    r.name as role_name
    FROM user as u
    INNER JOIN `role` as r on r.id_role = u.id_role
    WHERE u.name = ?
    ";

    // Getters
    public function getIdUser(){ return $this->id_user; }

    public function getName(){ return $this->name; }

    public function getEmail(){ return $this->email; }

    public function getIdRole()
    {
        return $this->id_role;
    }

    public function getUsername()
    {
        return $this->name; // Si el "username" es el nombre del usuario
    }

    // Setters
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setIdRole($id_role)
    {
        $this->id_role = $id_role;
    }

    public function setUsername($username)
    {
        $this->name = $username; // Establece el "username"
    }

    // Login function
    public static function Login($username, $password)
    {
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexion" . $connection->connect_error;
        }

        $command = $connection->prepare(self::$loginQuery);
        $command->bind_param('s', $username);
        $command->execute();
        $command->bind_result(
            $id_user,
            $name,
            $password_hash,
            $email,
            $rol_name
        );

        if ($command->fetch()) {
            if (sha1($password) == $password_hash) {
                if ($rol_name == 'Admin') {
                    return new User($id_user, $name, $email, $rol_name);
                }
            } else {
                return "Usuario o contraseña incorrectas";
            }
        } else {
            return "El usuario no existe";
        }
    }

    public static function LoginWeb($username, $password){
        $connection = Conexion::get_connection();
        if ($connection->connect_error) {
            return "Error en la conexion" . $connection->connect_error;
        }

        $command = $connection->prepare(self::$loginQuery);
        $command->bind_param('s', $username);
        $command->execute();
        $command->bind_result(
            $id_user,
            $name,
            $password_hash,
            $email,
            $rol_name
        );

        if ($command->fetch()) {
            if (sha1($password) == $password_hash) {
                if ($rol_name == 'Admin') {
                    return "admin";
                } else  {
                    return "user";
                }
            } else {
                return "Usuario o contraseña incorrectas";
            }
        } else {
            return "El usuario no existe";
        }
    }
}

?>