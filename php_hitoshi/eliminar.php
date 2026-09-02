<?php
require_once 'config/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = trim($_POST['id']);

    if (empty($id)) {
        $error = 'Datos inválidos.';
    } else {
        $conn     = getConnection();
        $id   = $conn->real_escape_string($id);

        $conn->query("DELETE FROM estudiantes WHERE id = '$id';");
        header('Location: main.php');
        exit();
    }
}
?>
