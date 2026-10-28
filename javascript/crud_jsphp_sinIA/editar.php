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

if (!$productos) {
    header('Location: index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="pico-main/css/pico.min.css">
</head>
<body>
<main class="container">
    <h1>Editar Producto</h1>
    <form action="editar_actualizar.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($productos['Id'] ?? $productos['id']) ?>">
        
        <label>Nuevo Nombre:
            <input type="text" name="nombre" value="<?= htmlspecialchars($productos['Nombre'] ?? $productos['nombre']) ?>" required>
        </label>
        
        <label>Nuevo Precio:
            <input type="number" step="0.01" min="0" name="precio" value="<?= htmlspecialchars($productos['Precio'] ?? $productos['precio']) ?>" required>
        </label>
        
        <label>Nuevo Stock:
            <input type="number" min="0" name="stock" value="<?= htmlspecialchars($productos['Stock'] ?? $productos['stock']) ?>" required>
        </label>
        
        <button type="submit">Guardar Cambios</button>
    </form>

    <a href="index.html">
        <button class="secondary outline" type="button">Volver</button>
    </a>
</main>
</body>
</html>