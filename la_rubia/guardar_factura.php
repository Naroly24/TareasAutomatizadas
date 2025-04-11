<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_recibo = $_POST['numero_recibo'];
    $fecha = date('Y-m-d');
    $codigo_cliente = $_POST['codigo_cliente'];
    $nombre_cliente = $_POST['nombre_cliente'];
    $total_pagar = $_POST['total_pagar'];
    $comentario = $_POST['comentario'] ?? '';
    $articulos = $_POST['articulo'];
    $cantidades = $_POST['cantidad'];
    $precios = $_POST['precio'];
    $totales = $_POST['total'];

    // Verificar o registrar cliente
    $stmt = $pdo->prepare("SELECT id FROM clientes WHERE codigo = ?");
    $stmt->execute([$codigo_cliente]);
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO clientes (codigo, nombre) VALUES (?, ?)");
        $stmt->execute([$codigo_cliente, $nombre_cliente]);
    }

    // Guardar factura
    $stmt = $pdo->prepare("INSERT INTO facturas (numero_recibo, fecha, codigo_cliente, total, comentario) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$numero_recibo, $fecha, $codigo_cliente, $total_pagar, $comentario]);
    $factura_id = $pdo->lastInsertId();

    // Guardar detalle de factura
    for ($i = 0; $i < count($articulos); $i++) {
        if (!empty($articulos[$i])) {
            $stmt = $pdo->prepare("INSERT INTO detalle_factura (factura_id, articulo_id, cantidad, precio, total) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$factura_id, $articulos[$i], $cantidades[$i], $precios[$i], $totales[$i]]);
        }
    }

    // Redirigir a generar recibo
// Al final de guardar_factura.php, ya incluido en el código anterior:
header("Location: generar_recibo.php?id=$factura_id");
exit;  
}
?>