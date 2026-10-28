<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getConnection();
    
    $id = $conn->real_escape_string($_POST['id'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = trim($_POST['precio'] ?? '');
    $stock = trim($_POST['stock'] ?? '');

    // Validación robusta en servidor: nombre no vacío, precio y stock numéricos válidos
    if (!empty($id) && !empty($nombre) && is_numeric($precio) && $precio >= 0 && ctype_digit($stock)) {
        $nombreEsc = $conn->real_escape_string($nombre);
        $precioEsc = (float)$precio;
        $stockEsc = (int)$stock;

        $sql = "UPDATE productos SET Nombre = '$nombreEsc', Precio = $precioEsc, Stock = $stockEsc WHERE Id = '$id'";
        
        if ($conn->query($sql)) {
            header('Location: index.html?success=editado');
            exit();
        } else {
            echo "Error al actualizar: " . $conn->error;
        }
    } else {
        echo "Datos inválidos (el precio y el stock deben ser numéricos). <a href='javascript:history.back()'>Volver</a>";
    }
}
?>