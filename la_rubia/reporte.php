<?php
require 'db_connect.php';

$fecha = date('Y-m-d');
$stmt = $pdo->prepare("SELECT COUNT(*) as facturas, SUM(total) as total_dinero FROM facturas WHERE fecha = ?");
$stmt->execute([$fecha]);
$reporte = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Diario - La Rubia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Reporte Diario - <?php echo date('d/m/Y'); ?></h1>
        <p><strong>Cantidad de Facturas:</strong> <?php echo $reporte['facturas']; ?></p>
        <p><strong>Total Cobrado:</strong> RD$<?php echo number_format($reporte['total_dinero'], 2); ?></p>
        <a href="index.php" class="btn btn-primary">Volver</a>
    </div>
</body>
</html>