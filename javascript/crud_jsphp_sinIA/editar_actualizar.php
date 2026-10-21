<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getConnection();
    
    $id = $conn->real_escape_string($_POST['id'] ?? '');
    $nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
    $precio = $conn->real_escape_string($_POST['precio'] ?? '');
    $stock = trim($_POST['stock'] ?? '');

    // Validar que no estén vacíos
    if (!empty($id) && !empty($nombre) && !empty($apellido) && !empty($email)) {
        $sql = "UPDATE productos SET Nombre = '$nombre', Precio = '$precio', Stock = '$stock' WHERE Id = '$id'";
        
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