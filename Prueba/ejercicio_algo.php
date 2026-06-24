<?php
// procesar.php

// Comprobar si se han enviado datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger el valor del campo "nombre"
    $nombre = $_POST['nombre'];
    
    // Mostrar el resultado
    echo "Hola, " . htmlspecialchars($nombre) . "!";
}
?>
