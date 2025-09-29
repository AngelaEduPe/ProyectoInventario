<?php
include(__DIR__ . '/../BarraMenu.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($subcategoria) ? "Editar Subcategoría" : "Nueva Subcategoría" ?></title>
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

        .card {
            border-radius: 15px;
        }

        .form-label {
            font-weight: 500;
        }

        input.form-control, select.form-control {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="content container mt-4">
        <h1 class="mb-4"><i class="bi bi-list-ul me-2"></i> <?= isset($subcategoria) ? "Editar Subcategoría" : "Nueva Subcategoría" ?></h1>
        <a href="index.php?c=subcategoria&a=listar" class="btn btn-outline-lila mb-3"><i class="bi bi-arrow-left-circle-fill"></i> Volver</a>

        <div class="card p-4 shadow-sm">
            <form method="post" action="index.php?c=subcategoria&a=<?= isset($subcategoria) ? 'actualizar' : 'guardar' ?>">
                <?php if(isset($subcategoria)): ?>
                    <input type="hidden" name="idSubcategoria" value="<?= htmlspecialchars($subcategoria->idSubcategoria) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="codigo" class="form-label">Código</label>
                    <input type="number" name="codigo" id="codigo" value="<?= htmlspecialchars($subcategoria->codigo ?? '') ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($subcategoria->nombre ?? '') ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="idCategoria" class="form-label">Categoría</label>
                    <select name="idCategoria" id="idCategoria" class="form-control" required>
                        <option value="">Seleccione una categoría</option>
                        <?php foreach($categorias as $c): ?>
                            <option value="<?= $c->idCategoria ?>" <?= (isset($subcategoria) && $subcategoria->idCategoria == $c->idCategoria) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c->nombre) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-lila btn-lg"><i class="bi bi-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
