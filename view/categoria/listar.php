<?php
include(__DIR__ . '/../BarraMenu.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Categorías</title>
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
        <h1 class="mb-4"><i class="bi bi-tags me-2"></i> Lista de Categorías</h1>

        <?php if ($rolUsuario === 'Administrador'): ?>
            <a href="index.php?c=categoria&a=crear" class="btn btn-lila mb-3">
                <i class="bi bi-plus-circle-fill"></i> Nueva Categoría
            </a>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <?php if ($rolUsuario === 'Administrador'): ?>
                            <th class="text-center">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($categorias)): ?>
                        <?php foreach($categorias as $c): ?>
                            <tr>
                                <td><?= htmlspecialchars($c->codigo) ?></td>
                                <td><?= htmlspecialchars($c->nombre) ?></td>

                                <?php if ($rolUsuario === 'Administrador'): ?>
                                    <td class="text-center">
                                        <a href="index.php?c=categoria&a=editar&id=<?= $c->idCategoria ?>" 
                                           class="btn btn-edit btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="index.php?c=categoria&a=eliminar&id=<?= $c->idCategoria ?>" 
                                           class="btn btn-delete btn-sm" 
                                           onclick="return confirm('¿Seguro de eliminar?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= ($rolUsuario === 'Administrador') ? 3 : 2 ?>" 
                                class="text-center text-muted">
                                No se encontraron categorías.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
