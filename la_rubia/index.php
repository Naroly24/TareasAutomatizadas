<?php
require 'db_connect.php';

// Obtener último número de recibo
$stmt = $pdo->query("SELECT MAX(id) as ultimo FROM facturas");
$ultimo = $stmt->fetch()['ultimo'] ?? 0;
$numero_recibo = 'REC-' . str_pad($ultimo + 1, 3, '0', STR_PAD_LEFT);

// Obtener artículos para el select
$articulos = $pdo->query("SELECT * FROM articulos")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Ventas - La Rubia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Sistema de Ventas - La Rubia</h1>
        <form method="POST" action="guardar_factura.php" id="facturaForm">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Fecha:</label>
                    <input type="text" class="form-control" value="<?php echo date('d/m/Y'); ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label>Nº Recibo:</label>
                    <input type="text" class="form-control" value="<?php echo $numero_recibo; ?>" readonly name="numero_recibo">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Código Cliente:</label>
                    <input type="text" class="form-control" name="codigo_cliente" id="codigo_cliente" onkeyup="buscarCliente()">
                </div>
                <div class="col-md-6">
                    <label>Nombre:</label>
                    <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente">
                </div>
            </div>
            <table class="table table-bordered" id="articulosTable">
                <thead>
                    <tr>
                        <th>Artículo</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="articulosBody">
                    <tr>
                        <td>
                            <select name="articulo[]" class="form-control articulo" onchange="actualizarPrecio(this)">
                                <option value="">Seleccionar</option>
                                <?php foreach ($articulos as $articulo): ?>
                                    <option value="<?php echo $articulo['id']; ?>" data-precio="<?php echo $articulo['precio']; ?>">
                                        <?php echo $articulo['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" name="cantidad[]" class="form-control cantidad" min="1" oninput="calcularTotal(this)"></td>
                        <td><input type="text" name="precio[]" class="form-control precio" readonly></td>
                        <td><input type="text" name="total[]" class="form-control total" readonly></td>
                        <td><button type="button" class="btn btn-danger" onclick="eliminarFila(this)">X</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-success mb-3" onclick="agregarFila()">+ Agregar Artículo</button>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Total a Pagar: RD$<span id="total_pagar">0</span></label>
                    <input type="hidden" name="total_pagar" id="total_pagar_input">
                </div>
            </div>
            <div class="mb-3">
                <label>Comentario:</label>
                <textarea class="form-control" name="comentario"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar e Imprimir</button>
            <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">Cancelar</button>
        </form>
        <a href="reporte.php" class="btn btn-info mt-3">Ver Reporte Diario</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts.js"></script>
</body>
</html>