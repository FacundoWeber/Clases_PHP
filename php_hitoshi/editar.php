<?php
require_once 'config/database.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: main.php');
    exit();
}

$conn = getConnection();
$id = $conn->real_escape_string($id);
$resultado = $conn->query("SELECT * FROM estudiantes WHERE id = $id");
$estudiante = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<body>

<link rel="stylesheet" href="pico-main/css/pico.min.css">

<form action="editar_actualizar.php" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($estudiante['id']) ?>">
    Nuevo Nombre: <input type="text" name="nombre" value="<?= htmlspecialchars($estudiante['nombre']) ?>"><br>
    Nuevo Apellido: <input type="text" name="apellido" value="<?= htmlspecialchars($estudiante['apellido']) ?>"><br>
    Nuevo E-mail: <input type="text" name="email" value="<?= htmlspecialchars($estudiante['email']) ?>"><br>
    <input type="submit" value="Guardar Cambios">
</form>

<a href="main.php">
    <button type="button">Volver</button>
</a>

</body>
</html>