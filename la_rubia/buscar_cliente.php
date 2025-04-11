<?php
require 'db_connect.php';

$codigo = $_GET['codigo'] ?? '';
$stmt = $pdo->prepare("SELECT nombre FROM clientes WHERE codigo = ?");
$stmt->execute([$codigo]);
$cliente = $stmt->fetch();

header('Content-Type: application/json');
echo json_encode(['nombre' => $cliente['nombre'] ?? '']);
?>