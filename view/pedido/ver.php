<?php
include(__DIR__ . '/../BarraMenu.php');
$base_url = "http://localhost/TPWEB/ProyectoInventario/";
$pedido = $pedido ?? null;

if (!$pedido) {
    echo '<div class="alert alert-danger">Pedido no encontrado.</div>';
    exit;
}

$encabezado = $pedido['encabezado'];
$detalles = $pedido['detalles'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Pedido #<?= $encabezado['idPedido'] ?></title>
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
            color: #007bff;
        }
        .card-header-custom {
            background-color: #007bff;
            color: white;
            font-weight: 600;
        }
        .table thead {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
<div class="content container mt-5">
    <h1 class="mb-4"><i class="bi bi-file-text"></i> Detalles del Pedido de Compra #<?= $encabezado['idPedido'] ?></h1>
    <hr>

    <div class="card mb-4 shadow">
        <div class="card-header card-header-custom">Información General</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6"><p><strong>Proveedor:</strong> <?= $encabezado['nombreProveedor'] ?></p></div>
                <div class="col-md-6"><p><strong>Estado:</strong> <span class="badge bg-primary"><?= $encabezado['estado'] ?></span></p></div>
                <div class="col-md-6"><p><strong>Fecha de Creación:</strong> <?= date('Y-m-d H:i', strtotime($encabezado['fechaCreacion'])) ?></p></div>
                <div class="col-md-6"><p><strong>Registrado por:</strong> <?= $encabezado['usuarioCreacion'] ?></p></div>
            </div>
        </div>
    </div>

    <div class="card mb-4 shadow">
        <div class="card-header card-header-custom">Detalle de Productos</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID Producto</th>
                            <th>Nombre</th>
                            <th>Cant. Pedida</th>
                            <th>Costo Inicial</th>
                            <th>Cant. Recibida</th>
                            <th>Fec. Vencimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalPedida = 0;
                        $totalRecibida = 0;
                        foreach ($detalles as $detalle): 
                            $totalPedida += $detalle['cantidadPedida'];
                            $totalRecibida += $detalle['cantidadRecibida'];
                        ?>
                        <tr>
                            <td><?= $detalle['idProducto'] ?></td>
                            <td><?= $detalle['nombreProducto'] ?></td>
                            <td><?= $detalle['cantidadPedida'] ?></td>
                            <td>$<?= number_format($detalle['costoInicial'], 2) ?></td>
                            <td><?= $detalle['cantidadRecibida'] ?? '0' ?></td>
                            <td><?= $detalle['fechaVencimiento'] ?: 'N/A' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-end">Totales:</th>
                            <th><?= $totalPedida ?></th>
                            <th></th>
                            <th><?= $totalRecibida ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="text-end">
        <a href="<?= $base_url ?>index.php?c=pedido&a=listar" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver al Listado</a>
        <?php if (in_array($encabezado['idEstado'], [1, 2])): ?>
            <a href="<?= $base_url ?>index.php?c=recepcion&a=form" class="btn btn-success"><i class="bi bi-box-seam"></i> Ir a Recepción</a>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>