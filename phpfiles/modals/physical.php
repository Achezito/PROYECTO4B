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

  
    // Getters
    public function getIdUser()
    {
        return $this->id_user;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getIdRole()
    {
        return $this->id_role;
    }

    public function getUsername()
    {
        return $this->name; // Si el "username" es el nombre del usuario
    }
}