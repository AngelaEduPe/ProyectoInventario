<?php
include(__DIR__ . '/../BarraMenu.php');
$base_url = "http://localhost/TPWEB/ProyectoInventario/";
$pedidos = $pedidos ?? [];
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Pedidos de Compra</title>
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

        .btn-primary-custom {
            background-color: #007bff;
            border: none;
            color: #fff;
            transition: 0.3s;
        }
        .btn-primary-custom:hover {
            background-color: #0056b3;
            color: #fff;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<div class="content container mt-5">
    <h1 class="mb-4"><i class="bi bi-list-check"></i> Pedidos de Compra Registrados</h1>
    <p class="text-muted">Gestión de todos los pedidos realizados a proveedores.</p>

    <?php if ($msg === 'success_registro_pedido'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> ¡Pedido registrado con éxito!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-end mb-3">
        <a href="<?= $base_url ?>index.php?c=pedido&a=form" class="btn btn-primary-custom">
            <i class="bi bi-cart-plus"></i> Registrar Nuevo Pedido
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="bg-primary text-white">
                <tr>
                    <th>ID Pedido</th>
                    <th>Proveedor</th>
                    <th>Fecha de Pedido</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pedidos)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No hay pedidos registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?= $pedido['idPedido'] ?></td>
                            <td><?= $pedido['nombreProveedor'] ?></td>
                            <td><?= date('Y-m-d', strtotime($pedido['fecha'])) ?></td>
                            <td><span class="badge bg-<?= strtolower($pedido['estado']) === 'pendiente' ? 'warning' : (strtolower($pedido['estado']) === 'recibido' ? 'success' : 'secondary') ?>"><?= $pedido['estado'] ?></span></td>
                            <td class="text-center">
                                <a href="<?= $base_url ?>index.php?c=pedido&a=ver&id=<?= $pedido['idPedido'] ?>" class="btn btn-sm btn-info text-white" title="Ver Detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>