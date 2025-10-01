<?php
include(__DIR__ . '/../BarraMenu.php');
$base_url = "http://localhost/TPWEB/ProyectoInventario/";
$movimientos = $movimientos ?? []; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Movimientos de Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url("<?= $base_url ?>view/imagenes/Fondo2.png") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }

        .content {
            margin-left: 270px;
            margin-top: 10px;
            max-width: 1230px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        
        h1 {
            font-weight: 600;
            color: #7245daff; 
        }
    </style>
</head>
<body>
<div class="content container mt-5">
    <h1 class="mb-4"><i class="bi bi-arrow-down-up"></i> Historial de Movimientos de Stock</h1>
    <p class="text-muted">Registro detallado de todos los ingresos y salidas de inventario, ordenados por fecha.</p>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="bg-primary text-white">
                <tr>
                    <th>ID Mov.</th>
                    <th>Fecha</th>
                    <th>Tipo de Movimiento</th>
                    <th>Referencia ID</th>
                    <th>Producto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-end">Costo Unitario</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($movimientos)): ?>
                    <?php foreach ($movimientos as $m): ?>
                        <?php 
                            $esIngreso = ($m['afectaStock'] === '+1');
                            $claseFila = $esIngreso ? 'table-success' : 'table-danger';
                            $signo = $esIngreso ? '+' : '-';
                            $claseBadge = $esIngreso ? 'bg-success' : 'bg-danger';
                        ?>
                        <tr class="<?= $claseFila; ?>">
                            <td><?= htmlspecialchars($m['idMovimiento']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime(htmlspecialchars($m['fecha']))) ?></td>
                            <td><span class="badge <?= $claseBadge ?>"><?= htmlspecialchars($m['tipoMovimiento']) ?></span></td>
                            <td><?= htmlspecialchars($m['documentoReferenciaId']) ?></td>
                            <td><?= htmlspecialchars($m['nombreProducto']) ?></td>
                            <td class="text-center">
                                <strong><?= $signo . htmlspecialchars($m['cantidad']) ?></strong>
                            </td>
                            <td class="text-end">
                                <?= '$' . number_format(floatval($m['precioUnitario']), 2, '.', ',') ?>
                            </td>
                            <td><?= htmlspecialchars($m['usuarioRegistro']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No se encontraron movimientos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>