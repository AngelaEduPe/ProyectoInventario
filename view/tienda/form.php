<?php
include(__DIR__ . '/../BarraMenu.php');

$base_url = "http://localhost/TPWEB/ProyectoInventario/";
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Tiendas</title>
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
            color: #fff;
            transform: scale(1.05);
        }

        .card-header-custom {
            background: linear-gradient(90deg, #6d28d9, #8b5cf6);
            color: white;
            font-weight: 600;
            border-bottom: none;
        }
    </style>
</head>
<body>
<div class="content container mt-5">
    <h1 class="mb-4"><i class="bi bi-shop"></i> Registrar Nueva Tienda/Ubicación</h1>
    <p class="text-muted">Gestión de puntos de venta y bodegas secundarias.</p>

    <?php if ($msg === 'success_insert'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> ¡Tienda registrada exitosamente!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php elseif ($msg === 'error_insert'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Error: No se pudo registrar la tienda. Verifique los datos.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= $base_url ?>index.php?c=tienda&a=insertar">
        
        <div class="card mb-4 shadow">
            <div class="card-header card-header-custom">Ubicación Geográfica</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="idDepartamento" class="form-label">Departamento <span class="text-danger">*</span></label>
                        <select id="idDepartamento" name="idDepartamento" class="form-select" required>
                            <option value="">Seleccione Departamento</option>
                            <?php 
                            if (!empty($departamentos)):
                                foreach ($departamentos as $depto): ?>
                                    <option value="<?= htmlspecialchars($depto['idDepartamento']) ?>">
                                        <?= htmlspecialchars($depto['nombre']) ?>
                                    </option>
                                <?php endforeach;
                            endif; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="idProvincia" class="form-label">Provincia <span class="text-danger">*</span></label>
                        <select id="idProvincia" name="idProvincia" class="form-select" required disabled>
                            <option value="">Seleccione Provincia</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="idDistrito" class="form-label">Distrito <span class="text-danger">*</span></label>
                        <select id="idDistrito" name="idDistrito" class="form-select" required disabled>
                            <option value="">Seleccione Distrito</option>
                        </select>
                        <input type="hidden" name="idDistrito" id="hiddenIdDistrito">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow">
            <div class="card-header card-header-custom">Detalles de la Tienda</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre de la Tienda <span class="text-danger">*</span></label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required maxlength="100">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" class="form-control" maxlength="20">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="direccion" class="form-label">Dirección Completa <span class="text-danger">*</span></label>
                        <input type="text" id="direccion" name="direccion" class="form-control" required maxlength="255" placeholder="Ej: Av. Las Flores 123, Urb. San Martín">
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-lila btn-lg w-100 mt-3"><i class="bi bi-save"></i> Registrar Tienda</button>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dptoSelect = document.getElementById('idDepartamento');
    const provSelect = document.getElementById('idProvincia');
    const distSelect = document.getElementById('idDistrito');
    const hiddenDistritoInput = document.getElementById('hiddenIdDistrito');
    const base_url = "<?= $base_url ?>";

    function loadUbigeoData(url, targetSelect, defaultText, valueKey, textKey) {
        targetSelect.innerHTML = `<option value="">${defaultText}</option>`;
        targetSelect.disabled = true;
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.length > 0) {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item[valueKey];
                        option.textContent = item[textKey];
                        targetSelect.appendChild(option);
                    });
                    targetSelect.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error fetching ubigeo data:', error);
                alert('Hubo un error al cargar las ubicaciones. Revise la consola.');
            });
    }

    dptoSelect.addEventListener('change', function() {
        const idDpto = this.value;
        provSelect.innerHTML = '<option value="">Seleccione Provincia</option>';
        distSelect.innerHTML = '<option value="">Seleccione Distrito</option>';
        distSelect.disabled = true;
        provSelect.disabled = true;
        hiddenDistritoInput.value = '';

        if (idDpto) {
            const url = `${base_url}index.php?c=ubigeo&a=provincias&id=${idDpto}`;
            loadUbigeoData(url, provSelect, 'Seleccione Provincia', 'idProvincia', 'nombre');
        }
    });

    provSelect.addEventListener('change', function() {
        const idProv = this.value;
        distSelect.innerHTML = '<option value="">Seleccione Distrito</option>';
        distSelect.disabled = true;
        hiddenDistritoInput.value = '';

        if (idProv) {
            const url = `${base_url}index.php?c=ubigeo&a=distritos&id=${idProv}`;
            loadUbigeoData(url, distSelect, 'Seleccione Distrito', 'idDistrito', 'nombre');
        }
    });
    
    distSelect.addEventListener('change', function() {
        hiddenDistritoInput.value = this.value;
    });

    const form = document.querySelector('#formTienda'); 
    form.addEventListener('submit', function(e) {
        if (!distSelect.value) {
            alert('Por favor, complete la selección de Departamento, Provincia y Distrito.');
            e.preventDefault(); 
        }
    });
});
</script>
</body>
</html>