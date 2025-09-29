<?php include(__DIR__ . "/../BarraMenu.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Stock por Fecha Vencimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .content {
            margin-left: 270px;
            padding: 20px;
        }
        .table-danger-light {
            background-color: #f8d7da !important;
        }
        .progress-container {
            width: 150px;
            margin-bottom: 0;
        }
        .progress-bar {
            transition: width 0.6s ease;
        }
        .card-header {
            background-color: #7245da;
            color: white;
            padding: 1.5rem;
            border-bottom: 3px solid #5a35b0;
        }
        .card-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .card-subtitle {
            font-size: 1rem;
            font-style: italic;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="card shadow-lg mb-4 rounded-3">
            <div class="card-header rounded-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="card-title">Reporte de Stock por Fecha Vencimiento</h1>
                        <p class="card-subtitle">Estado actual de tu inventario y alertas de existencias.</p>
                    </div>
                    <a href="index.php?c=stock&a=form" class="btn btn-light btn-lg">
                        <i class="bi bi-plus-circle me-2"></i> Agregar Stock
                    </a>
                </div>
            </div>
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
                <thead class="bg-primary text-white">
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