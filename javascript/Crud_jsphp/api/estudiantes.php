<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/database.php';
$conn = getConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Si viene un parámetro de búsqueda ?q=termino
    $q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

    if ($q !== '') {
        $sql = "SELECT * FROM estudiantes WHERE activo=1 AND (nombre LIKE '%$q%' OR apellido LIKE '%$q%') ORDER BY apellido";
    } else {
        $sql = "SELECT * FROM estudiantes WHERE activo=1 ORDER BY apellido";
    }

    $res = $conn->query($sql);
    $estudiantes = [];

    while ($row = $res->fetch_assoc()) {
        $estudiantes[] = $row;
    }

    echo json_encode($estudiantes);
    exit();

} elseif ($method === 'POST') {
    // Recibir los datos JSON enviados por fetch
    $data = json_decode(file_get_contents("php://input"), true);

    $nombre = $conn->real_escape_string($data['nombre'] ?? '');
    $apellido = $conn->real_escape_string($data['apellido'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');

    if (!empty($nombre) && !empty($apellido) && !empty($email)) {
        $sql = "INSERT INTO estudiantes (nombre, apellido, email, activo) VALUES ('$nombre', '$apellido', '$email', 1)";
        if ($conn->query($sql)) {
            echo json_encode(["success" => true, "message" => "Estudiante agregado con éxito"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al guardar en la base de datos"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Faltan datos obligatorios"]);
    }
    exit();

} elseif ($method === 'DELETE') {
    // Recibir el ID desde la URL (ej: api/estudiantes.php?id=5)
    $id = isset($_GET['id']) ? trim($_GET['id']) : '';

    if (empty($id)) {
        echo json_encode(["success" => false, "message" => "ID inválido o faltante"]);
        exit();
    }

    $conn = getConnection();
    $id = $conn->real_escape_string($id);

    // Tu consulta original de eliminación física
    $sql = "DELETE FROM estudiantes WHERE id = '$id'";

    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => "Estudiante eliminado con éxito"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al eliminar de la base de datos"]);
    }
    exit();
}