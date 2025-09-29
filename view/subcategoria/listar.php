<?php
include(__DIR__ . '/../BarraMenu.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Subcategorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <h1 class="mb-4"><i class="bi bi-tag"></i> Lista de Subcategorías</h1>

        <a href="index.php?c=subcategoria&a=crear" class="btn btn-lila mb-3">
            <i class="bi bi-plus-circle-fill"></i> Nueva Subcategoría
        </a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($subcategorias)): ?>
                        <?php foreach($subcategorias as $sc): ?>
                            <tr>
                                <td><?= htmlspecialchars($sc->codigo) ?></td>
                                <td><?= htmlspecialchars($sc->nombre) ?></td>
                                <td><?= htmlspecialchars($sc->categoria) ?></td>
                                <td class="text-center">
                                    <a href="index.php?c=subcategoria&a=editar&id=<?= $sc->idSubcategoria ?>" class="btn btn-edit btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn btn-delete btn-sm btn-eliminar" data-id="<?= $sc->idSubcategoria ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">No se encontraron subcategorías.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Paginación" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= ($i == ($paginaActual ?? 1)) ? 'active' : '' ?>">
                        <a class="page-link" href="index.php?c=subcategoria&a=listar&pagina=<?= $i ?><?= htmlspecialchars($termino ? '&q=' . $termino : '') ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-eliminar').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#8b5cf6',
                        cancelButtonColor: '#ec4899',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `index.php?c=subcategoria&a=eliminar&id=${id}`;
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
