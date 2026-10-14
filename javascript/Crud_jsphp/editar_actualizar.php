<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getConnection();
    
    $id = $conn->real_escape_string($_POST['id'] ?? '');
    $nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
    $apellido = $conn->real_escape_string($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validar que no estén vacíos y que el email tenga un formato válido (@, dominio, etc.)
    if (!empty($id) && !empty($nombre) && !empty($apellido) && !empty($email)) {
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Error: El formato del correo electrónico no es válido. <a href='javascript:history.back()'>Volver</a>";
            exit();
        }

        $email_escaped = $conn->real_escape_string($email);
        $sql = "UPDATE estudiantes SET nombre = '$nombre', apellido = '$apellido', email = '$email_escaped' WHERE id = '$id'";
        
        if ($conn->query($sql)) {
            header('Location: index.html?success=editado');
            exit();
        } else {
            echo "Error al actualizar: " . $conn->error;
        }
    } else {
        echo "Faltan datos obligatorios. <a href='javascript:history.back()'>Volver</a>";
    }
}