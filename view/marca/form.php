<?php
include(__DIR__ . '/../BarraMenu.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($marca) ? "Editar Marca" : "Nueva Marca" ?></title>
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
    <h1 class="mb-4"><?= isset($marca) ? "Editar Marca" : "Nueva Marca" ?></h1>
    <a href="index.php?c=marca&a=listar" class="btn btn-outline-lila btn-back">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver a la Lista
    </a>
    <div class="card p-4 shadow-sm">
        <form method="post" action="index.php?c=marca&a=<?= isset($marca) ? 'actualizar' : 'guardar' ?>">
            <?php if(isset($marca)): ?>
                <input type="hidden" name="idMarca" value="<?= htmlspecialchars($marca->idMarca) ?>">
            <?php endif; ?>

            <div class="form-group mb-3">
                <label for="nombre" class="form-label">Nombre de la Marca</label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($marca->nombre ?? '') ?>" class="form-control" required>
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