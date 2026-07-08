<?php
require_once 'config/database.php';

$res=$conn->query("SELECT nombre FROM estudiantes WHERE nombre= ?");

$stmt->bind_param('s', $nombre);
$stmt->execute();
$res = $stmt->get_result();
?>
<table>
  <thead><tr><th>Nombre</th></tr></thead>
  <tbody>
  <?php while ($f = $res->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($f['nombre']) ?></td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
<p>Total: <?= $res->num_rows ?> estudiantes</p>