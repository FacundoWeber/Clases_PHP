<?php
// Evitar que errores de PHP rompan la salida JSON
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/database.php';
$conn = getConnection();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Recibir el JSON enviado desde el frontend con los productos seleccionados
    $data = json_decode(file_get_contents("php://input"), true);
    $productosVenta = $data['productos'] ?? [];

    if (empty($productosVenta)) {
        echo json_encode(["success" => false, "message" => "El carrito de ventas está vacío."]);
        exit();
    }

    // Iniciamos una transacción: o se guarda todo o no se guarda nada
    $conn->begin_transaction();

    try {
        $totalVenta = 0;
        $itemsValidados = [];

        // 1. Validar existencias, precios y stock en la tabla 'productos'
        foreach ($productosVenta as $item) {
            $idProducto = (int)$item['id_producto'];
            $cantidadComprada = (int)$item['cantidad'];

            if ($cantidadComprada <= 0) {
                throw new Exception("Cantidad inválida para uno de los productos.");
            }

            $stmt = $conn->prepare("SELECT Precio, Stock FROM productos WHERE Id = ? AND Activo = 1");
            $stmt->bind_param("i", $idProducto);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows === 0) {
                throw new Exception("Un producto seleccionado ya no existe en el inventario.");
            }

            $prod = $res->fetch_assoc();
            $precioUnitario = (float)$prod['Precio'];
            $stockActual = (int)$prod['Stock'];

            // Validar stock disponible
            if ($stockActual < $cantidadComprada) {
                throw new Exception("Stock insuficiente para el producto ID: $idProducto (Disponible: $stockActual)");
            }

            $subtotal = $precioUnitario * $cantidadComprada;
            $totalVenta += $subtotal;

            $itemsValidados[] = [
                "id_producto" => $idProducto,
                "cantidad" => $cantidadComprada,
                "precio_unitario" => $precioUnitario,
                "subtotal" => $subtotal
            ];
        }

        // 2. Insertar la cabecera en la tabla 'Ventas' (solo la primera en mayúscula)
        $stmtVenta = $conn->prepare("INSERT INTO Ventas (Fecha, Total) VALUES (NOW(), ?)");
        $stmtVenta->bind_param("d", $totalVenta);
        $stmtVenta->execute();
        $idVenta = $conn->insert_id;

        // 3. Insertar los ítems en la tabla de detalle (ajusta 'detalle_ventas' si también tiene mayúscula inicial)
        $stmtDetalle = $conn->prepare("INSERT INTO detalle_ventas (id_venta, id_producto, Cantidad, Precio, Subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmtStock = $conn->prepare("UPDATE productos SET Stock = Stock - ? WHERE Id = ?");

        foreach ($itemsValidados as $item) {
            // Guardar detalle con las columnas exactas
            $stmtDetalle->bind_param("iiidd", $idVenta, $item['id_producto'], $item['cantidad'], $item['precio_unitario'], $item['subtotal']);
            $stmtDetalle->execute();

            // Descontar stock
            $stmtStock->bind_param("ii", $item['cantidad'], $item['id_producto']);
            $stmtStock->execute();
        }

        // Confirmar transacción
        $conn->commit();
        echo json_encode(["success" => true, "message" => "¡Venta registrada con éxito!", "id_venta" => $idVenta]);

    } catch (Exception $e) {
        // Revertir cambios si hay algún error
        $conn->rollback();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
    exit();
}
?>