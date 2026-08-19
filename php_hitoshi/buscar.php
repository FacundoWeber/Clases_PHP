<?php
require_once 'config/database.php';


$nombre = $_POST['nombre'];

$conn = getConnection();

$parametro = "%" . $nombre . "%";

$stmt = $conn->prepare("SELECT nombre,apellido,email FROM estudiantes WHERE nombre LIKE ?");
$stmt->bind_param("s", $parametro);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<link rel="stylesheet" href="pico-main/css/pico.min.css">

<table>
  <thead><tr><th>Nombre</th><th>Apellido</th><th>Correo</th><thead>
  <tbody>
  <?php while ($f = $resultado->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($f['nombre']) ?></td>
      <td><?= htmlspecialchars($f['apellido']) ?></td>
      <td><?= htmlspecialchars($f['email']) ?></td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
<p>Total: <?= $resultado->num_rows ?> estudiantes</p>

<a href="main.php">
        <button type="button">Volver</button>
    </a>