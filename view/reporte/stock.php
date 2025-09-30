<?php
include(__DIR__ . '/../BarraMenu.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Stock por Fecha Vencimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url("view/imagenes/Fondo2.png") no-repeat center center fixed;
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
            color: #6d28d9;
        }
        p {
            color: #6b7280;
        }

        .btn-lila {
            background-color: #8b5cf6;
            border: none;
            color: #fff;
            transition: 0.3s;
        }
        .btn-lila:hover {
            background-color: #7c3aed;
            transform: scale(1.05);
        }

        .table thead {
            background: #6d28d9 !important;
            color: #fff;
        }
        .table tbody tr:hover {
            background-color: rgba(139, 92, 246, 0.1);
        }

        .table-danger-light {
            background-color: rgba(248, 215, 218, 0.6) !important;
        }

        .progress-container {
            width: 150px;
            margin-bottom: 0;
        }
        .progress-bar {
            transition: width 0.6s ease;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Reporte de Stock por Fecha Vencimiento</h1>
                <p>Estado actual de tu inventario y alertas de existencias.</p>
            </div>
            <a href="index.php?c=stock&a=form" class="btn btn-lila btn-lg">
                <i class="bi bi-plus-circle me-2"></i> Agregar Stock
            </a>
        </div>

        <?php
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] === 'success') {
                echo '<div class="alert alert-success">¡Stock agregado correctamente!</div>';
            } else if ($_GET['msg'] === 'error') {
                echo '<div class="alert alert-danger">Hubo un error al agregar el stock.</div>';
            }
        }

        $stockTotales = [];
        if (!empty($data)) {
            foreach ($data as $item) {
                $idProducto = $item['idProducto'] ?? null;
                if ($idProducto !== null) {
                    if (!isset($stockTotales[$idProducto])) {
                        $stockTotales[$idProducto] = 0;
                    }
                    $stockTotales[$idProducto] += $item['cantidad'];
                }
            }
        }
        ?>

        <div class="table-responsive rounded-3 shadow-sm">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th scope="col">Producto</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">SKU</th>
                        <th scope="col">Stock Actual</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Fecha de Vencimiento</th>
                        <th scope="col">Alerta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $item): 
                            $idProducto = $item['idProducto'] ?? null;
                            $cantidadActualTotal = ($idProducto !== null) ? ($stockTotales[$idProducto] ?? 0) : 0;
                            $stockMinimo = $item['stockMinimo'] ?? 0;
                            
                            $alerta = false;
                            $porcentaje = 0;

                            if ($stockMinimo > 0) {
                                $porcentaje = min(100, ($cantidadActualTotal / $stockMinimo) * 100);
                            } else if ($cantidadActualTotal > 0) {
                                $porcentaje = 100;
                            }
                            
                            $barraClase = 'bg-success';
                            if ($cantidadActualTotal <= $stockMinimo * 0.5) {
                                $barraClase = 'bg-danger';
                                $alerta = true;
                            } else if ($cantidadActualTotal <= $stockMinimo) {
                                $barraClase = 'bg-warning';
                                $alerta = true;
                            } else {
                                $barraClase = 'bg-success';
                            }
                        ?>
                        <tr class="<?= $alerta ? 'table-danger-light' : ''; ?>">
                            <td><?php echo htmlspecialchars($item['nombreProducto'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($item['nombreCategoria'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($item['sku'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($item['cantidad'] ?? 0); ?></td>
                            <td>
                                <div class="progress progress-container">
                                    <div class="progress-bar <?= $barraClase; ?>" 
                                         role="progressbar" 
                                         style="width: <?= $porcentaje; ?>%" 
                                         aria-valuenow="<?= $porcentaje; ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted"><?= htmlspecialchars($cantidadActualTotal) . " / " . htmlspecialchars($stockMinimo); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($item['fechaVencimiento'] ?? 'N/A'); ?></td>
                            <td>
                                <?php if ($alerta): ?>
                                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay productos en stock para mostrar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
