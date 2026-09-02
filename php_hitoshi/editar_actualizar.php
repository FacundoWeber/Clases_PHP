<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = trim($_POST['id']);
    $nombre   = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email    = trim($_POST['email']);

    if (!empty($id) && !empty($nombre) && !empty($apellido) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $conn     = getConnection();
        $id       = $conn->real_escape_string($id);
        $nombre   = $conn->real_escape_string($nombre);
        $apellido = $conn->real_escape_string($apellido);
        $email    = $conn->real_escape_string($email);
        
        $conn->query("UPDATE `estudiantes` 
                    SET `nombre` = '$nombre', `apellido` = '$apellido', `email` = '$email' 
                    WHERE `id` = $id;");
    }
}

header('Location: main.php');
exit();
?>