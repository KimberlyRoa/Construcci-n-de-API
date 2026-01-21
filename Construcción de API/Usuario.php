<?php
/**
 * Modelo Usuario
 * Encargado de interactuar con la tabla usuarios
 */

class Usuario {

    private $conn;
    private $tabla = "usuarios";

    public $usuario;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Registrar usuario
    public function registrar() {
        $query = "INSERT INTO " . $this->tabla . " (usuario, password)
                  VALUES (:usuario, :password)";

        $stmt = $this->conn->prepare($query);

        // Encriptar contraseña
        $password_encriptada = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":password", $password_encriptada);

        return $stmt->execute();
    }

    // Validar login
    public function login() {
        $query = "SELECT * FROM " . $this->tabla . " WHERE usuario = :usuario LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->execute();

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar contraseña
        if ($fila && password_verify($this->password, $fila["password"])) {
            return true;
        }

        return false;
    }
}