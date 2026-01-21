<?php
/**
 * Controlador de autenticación
 * Gestiona registro e inicio de sesión
 */

require_once "config/database.php";
require_once "models/Usuario.php";

$database = new Database();
$db = $database->conectar();

$usuario = new Usuario($db);

// Obtener datos enviados en formato JSON
$data = json_decode(file_get_contents("php://input"));

// Identificar acción solicitada
$accion = $_GET["accion"] ?? "";

if ($accion == "registro") {

    // Asignar datos
    $usuario->usuario = $data->usuario;
    $usuario->password = $data->password;

    // Registrar usuario
    if ($usuario->registrar()) {
        echo json_encode([
            "mensaje" => "Usuario registrado correctamente"
        ]);
    } else {
        echo json_encode([
            "error" => "No se pudo registrar el usuario"
        ]);
    }

} elseif ($accion == "login") {

    // Asignar datos
    $usuario->usuario = $data->usuario;
    $usuario->password = $data->password;

    // Validar credenciales
    if ($usuario->login()) {
        echo json_encode([
            "mensaje" => "Autenticación satisfactoria"
        ]);
    } else {
        echo json_encode([
            "error" => "Error en la autenticación"
        ]);
    }

} else {
    echo json_encode([
        "error" => "Acción no válida"
    ]);
}