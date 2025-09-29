<?php
include(__DIR__ . '/../BarraMenu.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($producto) ? "Editar Producto" : "Nuevo Producto" ?></title>
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
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .form-label {
            font-weight: 500;
        }

        .form-control:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 0.2rem rgba(139,92,246,0.25);
        }

        .btn-back {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="content container mt-4">
    <h1 class="mb-4"><?= isset($producto) ? "Editar Producto" : "Nuevo Producto" ?></h1>
    <a href="index.php?c=producto&a=listar" class="btn btn-outline-lila btn-back">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver a la Lista
    </a>
    <div class="card p-4 shadow-sm">
        <form method="post" action="index.php?c=producto&a=<?= isset($producto) ? 'actualizar' : 'guardar' ?>">
            <?php if(isset($producto)): ?>
                <input type="hidden" name="idProducto" value="<?= htmlspecialchars($producto->idProducto) ?>">
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="codigoBarras" class="form-label">Código de Barras</label>
                        <input type="text" name="codigoBarras" id="codigoBarras" value="<?= htmlspecialchars($producto->codigoBarras ?? '') ?>" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($producto->nombre ?? '') ?>" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="3"><?= htmlspecialchars($producto->descripcion ?? '') ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="idSubcategoria" class="form-label">Subcategoría</label>
                        <select name="idSubcategoria" id="idSubcategoria" class="form-control" required>
                            <option value="">Seleccione una subcategoría</option>
                            <?php foreach($subcategorias as $sub): ?>
                                <option value="<?= htmlspecialchars($sub->idSubcategoria) ?>" <?= (isset($producto) && $producto->idSubcategoria == $sub->idSubcategoria) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($sub->nombre) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="idMarca" class="form-label">Marca</label>
                        <select name="idMarca" id="idMarca" class="form-control" required>
                            <option value="">Seleccione una marca</option>
                            <?php foreach($marcas as $m): ?>
                                <option value="<?= htmlspecialchars($m->idMarca) ?>" <?= (isset($producto) && $producto->idMarca == $m->idMarca) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m->nombre) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="stockMinimo" class="form-label">Stock Mínimo</label>
                        <input type="number" name="stockMinimo" id="stockMinimo" value="<?= htmlspecialchars($producto->stockMinimo ?? 0) ?>" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="precioCosto" class="form-label">Precio Costo</label>
                        <input type="number" step="0.01" name="precioCosto" id="precioCosto" value="<?= htmlspecialchars($producto->precioCosto ?? 0) ?>" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-lila btn-lg">
                    <i class="bi bi-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
