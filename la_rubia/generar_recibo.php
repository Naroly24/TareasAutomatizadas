<?php
require 'db_connect.php';
require 'dompdf/autoload.inc.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

$id = $_GET['id'] ?? 0;

// Consultar factura y detalles
$stmt = $pdo->prepare("SELECT f.*, c.nombre FROM facturas f JOIN clientes c ON f.codigo_cliente = c.codigo WHERE f.id = ?");
$stmt->execute([$id]);
$factura = $stmt->fetch();

$stmt = $pdo->prepare("SELECT d.*, a.nombre FROM detalle_factura d JOIN articulos a ON d.articulo_id = a.id WHERE d.factura_id = ?");
$stmt->execute([$id]);
$detalles = $stmt->fetchAll();

// Crear el HTML del recibo
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo ' . $factura['numero_recibo'] . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { width: 80%; margin: auto; }
        .header { text-align: center; border-bottom: 2px solid #343a40; padding-bottom: 10px; }
        .header h1 { color: #343a40; margin: 0; }
        .info { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #dee2e6; padding: 8px; text-align: left; }
        th { background-color: #343a40; color: white; }
        .total { font-weight: bold; font-size: 1.2em; }
        .comentario { font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Recibo ' . $factura['numero_recibo'] . '</h1>
            <p>La Rubia - Sistema de Ventas</p>
        </div>
        <div class="info">
            <p><strong>Fecha:</strong> ' . date('d/m/Y', strtotime($factura['fecha'])) . '</p>
            <p><strong>Cliente:</strong> ' . htmlspecialchars($factura['nombre']) . ' (' . htmlspecialchars($factura['codigo_cliente']) . ')</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Artículo</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>';
foreach ($detalles as $detalle) {
    $html .= '
                <tr>
                    <td>' . htmlspecialchars($detalle['nombre']) . '</td>
                    <td>' . $detalle['cantidad'] . '</td>
                    <td>RD$' . number_format($detalle['precio'], 2) . '</td>
                    <td>RD$' . number_format($detalle['total'], 2) . '</td>
                </tr>';
}
$html .= '
            </tbody>
        </table>
        <p class="total">Total a Pagar: RD$' . number_format($factura['total'], 2) . '</p>
        <p class="comentario"><strong>Comentario:</strong> ' . (empty($factura['comentario']) ? 'Ninguno' : htmlspecialchars($factura['comentario'])) . '</p>
    </div>
</body>
</html>';

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

$dompdf->stream("recibo_{$factura['numero_recibo']}.pdf", ["Attachment" => true]);

echo $html . '<button onclick="window.print()">Imprimir</button>';
?>