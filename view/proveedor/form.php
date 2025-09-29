<?php
include(__DIR__ . '/../BarraMenu.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($proveedor) ? "Editar Proveedor" : "Nuevo Proveedor" ?></title>
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
<div class="content container mt-4">
    <h1 class="mb-4"><?= isset($proveedor) ? "Editar Proveedor" : "Nuevo Proveedor" ?></h1>
    <a href="index.php?c=proveedor&a=listar" class="btn btn-outline-lila btn-back">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver a la Lista
    </a>
    <div class="card p-4 shadow-sm">
        <form method="post" action="index.php?c=proveedor&a=<?= isset($proveedor) ? 'actualizar' : 'guardar' ?>">
            <?php if(isset($proveedor)): ?>
                <input type="hidden" name="idProveedor" value="<?= htmlspecialchars($proveedor->idProveedor) ?>">
            <?php endif; ?>

            <div class="form-group mb-3">
                <label for="numeroDocumento" class="form-label">Número de Documento</label>
                <input type="text" name="numeroDocumento" id="numeroDocumento" value="<?= htmlspecialchars($proveedor->numeroDocumento ?? '') ?>" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="razonSocial" class="form-label">Razón Social</label>
                <input type="text" name="razonSocial" id="razonSocial" value="<?= htmlspecialchars($proveedor->razonSocial ?? '') ?>" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="nombreComercial" class="form-label">Nombre Comercial</label>
                <input type="text" name="nombreComercial" id="nombreComercial" value="<?= htmlspecialchars($proveedor->nombreComercial ?? '') ?>" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="contacto" class="form-label">Contacto</label>
                <input type="text" name="contacto" id="contacto" value="<?= htmlspecialchars($proveedor->contacto ?? '') ?>" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" value="<?= htmlspecialchars($proveedor->telefono ?? '') ?>" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <textarea name="direccion" id="direccion" class="form-control" rows="3"><?= htmlspecialchars($proveedor->direccion ?? '') ?></textarea>
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
