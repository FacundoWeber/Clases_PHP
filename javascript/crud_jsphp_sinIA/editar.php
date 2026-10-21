<?php
require_once 'config/database.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.html');
    exit();
}

$conn = getConnection();
$id = $conn->real_escape_string($id);
$resultado = $conn->query("SELECT * FROM productos WHERE Id = $id");
$productos = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<body>

<link rel="stylesheet" href="pico-main/css/pico.min.css">

<form action="editar_actualizar.php" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($productos['id']) ?>">
    Nuevo Nombre: <input type="text" name="nombre" value="<?= htmlspecialchars($productos['nombre']) ?>"><br>
    Nuevo Precio: <input type="text" name="precio" value="<?= htmlspecialchars($productos['precio']) ?>"><br>
    Nuevo Stock: <input type="text" name="stock" value="<?= htmlspecialchars($productos['stock']) ?>"><br>
    <input type="submit" value="Guardar Cambios">
</form>

<a href="index.html">
    <button type="button">Volver</button>
</a>

</body>
</html>