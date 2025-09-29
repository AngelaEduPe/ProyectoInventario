<?php
include(__DIR__ . '/../BarraMenu.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Proveedores</title>
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

        .btn-outline-lila {
            border: 1px solid #8b5cf6;
            color: #6d28d9;
            transition: 0.3s;
        }
        .btn-outline-lila:hover {
            background-color: #8b5cf6;
            color: #fff;
        }

        .btn-edit {
            background-color: #8b5cf6;
            border: none;
            color: #fff;
            transition: 0.3s;
        }
        .btn-edit:hover {
            background-color: #7c3aed; 
            transform: scale(1.05);
        }

        .btn-delete {
            background-color: #ec4899;
            border: none;
            color: #fff;
            transition: 0.3s;
        }
        .btn-delete:hover {
            background-color: #db2777;
            transform: scale(1.05);
        }

        table thead {
            background: #6d28d9 !important;
            color: #fff;
        }
        table tbody tr:hover {
            background-color: rgba(139, 92, 246, 0.1);
        }

        .pagination .page-link {
            color: #6d28d9;
        }
        .pagination .active .page-link {
            background-color: #8b5cf6;
            border-color: #8b5cf6;
        }
    </style>
</head>
<body>
<div class="content container mt-5">
    <h1 class="mb-4"><i class="bbi bi-truck"></i> Lista de Proveedores</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['mensaje']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="index.php?c=proveedor&a=crear" class="btn btn-lila">
            <i class="bi bi-plus-circle-fill"></i> Nuevo Proveedor
        </a>
        <form action="index.php" method="get" class="d-flex">
            <input type="hidden" name="c" value="proveedor">
            <input type="hidden" name="a" value="listar">
            <input type="text" name="q" class="form-control me-2" placeholder="Buscar por nombre o documento..." 
                   value="<?= htmlspecialchars($terminoBusqueda ?? '') ?>">
            <button class="btn btn-outline-lila" type="submit"><i class="bi bi-search"></i> Buscar</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número Documento</th>
                    <th>Razón Social</th>
                    <th>Nombre Comercial</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($proveedores)): ?>
                    <?php foreach ($proveedores as $prov): ?>
                        <tr>
                            <td><?= htmlspecialchars($prov->idProveedor) ?></td>
                            <td><?= htmlspecialchars($prov->numeroDocumento) ?></td>
                            <td><?= htmlspecialchars($prov->razonSocial) ?></td>
                            <td><?= htmlspecialchars($prov->nombreComercial) ?></td>
                            <td><?= htmlspecialchars($prov->contacto) ?></td>
                            <td><?= htmlspecialchars($prov->telefono) ?></td>
                            <td><?= htmlspecialchars($prov->direccion) ?></td>
                            <td class="text-center">
                                <a href="index.php?c=proveedor&a=editar&id=<?= htmlspecialchars($prov->idProveedor) ?>" 
                                   class="btn btn-edit btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="index.php?c=proveedor&a=eliminar&id=<?= htmlspecialchars($prov->idProveedor) ?>"
                                   class="btn btn-delete btn-sm"
                                   onclick="return confirm('¿Está seguro de que desea eliminar este proveedor?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No se encontraron proveedores.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <nav aria-label="Paginación" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= ($i == ($paginaActual ?? 1)) ? 'active' : '' ?>">
                    <a class="page-link" href="index.php?c=proveedor&a=listar&pagina=<?= $i ?><?= htmlspecialchars($terminoBusqueda ? '&q=' . $terminoBusqueda : '') ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
