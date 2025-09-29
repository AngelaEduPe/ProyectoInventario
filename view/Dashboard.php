<?php 
include("BarraMenu.php"); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Inicio</title>
    
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
            margin-top: 20px;
            padding: 20px;
        }
        h1 {
            font-weight: 600;
            color: #6d28d9;
        }
        .card-dashboard {
            border: none;
            border-radius: 20px;
            padding: 25px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        .card-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .bg-purple { background-color: #6d28d9; }
        .bg-pink { background-color: #e83e8c; }
        .bg-violet { background-color: #9f66cc; }
        .bg-orange { background-color: #fd7e14; }
        
        .low-stock-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .low-stock-list .list-group-item {
            border: none;
            border-top: 1px solid #eee;
            padding: 10px 0;
        }
    </style>
</head>
<body>
<div class="content">
    <h1>Dashboard Principal</h1>
    <div class="row g-4">
        
        <div class="col-md-6 col-lg-3">
            <a href="<?= $base_url ?? '' ?>index.php?c=producto&a=listar" class="text-decoration-none">
                <div class="card-dashboard">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-purple me-3">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Productos</h5>
                            <h3 class="mb-0"><?php echo $totalStock; ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="<?= $base_url ?? '' ?>index.php?c=proveedor&a=listar" class="text-decoration-none">
                <div class="card-dashboard">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-pink me-3">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Proveedores</h5>
                            <h3 class="mb-0"><?php echo $totalProv; ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <?php if ($rolConectado === 'Administrador'): ?>
            <div class="col-md-6 col-lg-3">
                <a href="<?= $base_url ?? '' ?>index.php?c=usuario&a=listar" class="text-decoration-none">
                    <div class="card-dashboard">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-violet me-3">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Total Usuarios</h5>
                                <h3 class="mb-0"><?php echo $totalUser; ?></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endif; ?>
        <div class="col-md-6 col-lg-3">
            <div class="card-dashboard">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-orange me-3">
                        <i class="bi bi-activity"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">En línea</h5>
                        <h3 class="mb-0"><?php echo $totalOnline; ?></h3>
                        <div class="d-flex flex-column">
                            <small class="text-muted">
                                <?php echo "Usuario: " . htmlspecialchars($usuarioConectado); ?>
                            </small>
                            <small class="text-muted">
                                <?php echo "Rol: " . htmlspecialchars($rolConectado); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-4">
            <div class="card-dashboard">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-circle bg-danger me-3">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 text-danger">Alerta de Stock Bajo</h5>
                        <p class="mb-0 text-muted">Productos que necesitan ser reabastecidos.</p>
                    </div>
                </div>
                
                <?php 
                if (isset($productosBajoStock) && !empty($productosBajoStock)): 
                ?>
                    <ul class="list-group low-stock-list">
                        <?php foreach ($productosBajoStock as $producto): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><?php echo htmlspecialchars($producto['nombre']); ?></span>
                                <span class="badge bg-danger rounded-pill"><?php echo htmlspecialchars($producto['stockTotal']); ?> / <?php echo htmlspecialchars($producto['stockMinimo']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-success text-center">
                        ¡Todos los productos están con stock suficiente!
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <div class="text-center mt-4">
        <img src="view/imagenes/Marcas.png" alt="Marcas" class="img-fluid rounded shadow" style="max-height:500px;">
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>