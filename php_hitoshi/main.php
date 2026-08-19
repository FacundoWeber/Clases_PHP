
<link rel="stylesheet" href="pico-main/css/pico.min.css">

<style> 
  button {
    --pico-line-height: 0.5;
  }
</style>


<?php
require_once 'config/database.php';
$conn = getConnection();
$res  = $conn->query('SELECT * FROM estudiantes WHERE activo=1 ORDER BY apellido');
?>
<table>
  <thead><tr><th>Nombre</th><th>Email</th></tr></thead>
  <tbody>
  <?php while ($f = $res->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($f['nombre'].' '.$f['apellido']) ?></td>
      <td><?= htmlspecialchars($f['email']) ?></td>
      <td>
        <form action="eliminar.php" method="POST" style="margin:0;">
          <input type="hidden" name="nombre" value="<?= htmlspecialchars($f['nombre']) ?>">
          <input type="hidden" name="apellido" value="<?= htmlspecialchars($f['apellido']) ?>">
          <input type="hidden" name="email" value="<?= htmlspecialchars($f['email']) ?>">
          <button type="submit" class="secondary" onclick="return confirm('¿Estás seguro de eliminar a <?= htmlspecialchars($f['nombre']) ?>?');">Eliminar</button>
        </form>
      </td>
      <td>
        <form action="Editar.php" method="POST" style="margin:0;"> 
         <input type="hidden" name="nombre" value="<?= htmlspecialchars($f['nombre']) ?>">
          <input type="hidden" name="apellido" value="<?= htmlspecialchars($f['apellido']) ?>">
          <input type="hidden" name="email" value="<?= htmlspecialchars($f['email']) ?>">
          <button type="submit" class="secondary";>Editor</button>
        </form>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
<p>Total: <?= $res->num_rows ?> estudiantes</p>

<a href="insertar.html">
        <button type="button">Insertar</button>
    </a>
<a href="buscar.html">
        <button type="button">Filtrar</button>
    </a>
