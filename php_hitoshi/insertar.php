<?php
require_once 'config/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email    = trim($_POST['email']);

    if (empty($nombre) || empty($apellido) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Datos inválidos.';
    } else {
        $conn     = getConnection();
        $nombre   = $conn->real_escape_string($nombre);
        $apellido = $conn->real_escape_string($apellido);
        $email    = $conn->real_escape_string($email);
        $conn->query("INSERT INTO estudiantes (nombre,apellido,email)
                      VALUES ('$nombre','$apellido','$email')");
        header('Location: insertar.html');
        exit;
    }
}
?>
