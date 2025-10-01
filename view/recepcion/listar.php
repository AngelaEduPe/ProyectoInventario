<?php
include(__DIR__ . '/../BarraMenu.php');

$base_url = "http://localhost/TPWEB/ProyectoInventario/";
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recepción de Pedidos de Compra</title>
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
            color: #28a745;
        }
        .btn-success-custom {
            background-color: #28a745;
            border: none;
            color: #fff;
            transition: 0.3s;
        }
        .btn-success-custom:hover {
            background-color: #1e7e34;
            color: #fff;
            transform: scale(1.05);
        }
        .card-header-custom {
            background: linear-gradient(90deg, #28a745, #2abf48);
            color: white;
            font-weight: 600;
            border-bottom: none;
        }
        .table thead {
            background: #28a745 !important;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="content container mt-5">
    <h1 class="mb-4"><i class="bi bi-box-seam"></i> Recepción de Stock por Pedido</h1>
    <p class="text-muted">Busque un pedido pendiente para registrar los productos recibidos e ingresar el stock.</p>

    <div id="alert-messages" class="mb-4"></div>

    <div class="card mb-4 shadow">
        <div class="card-header card-header-custom">Buscar Pedido</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="idPedido" class="form-label">ID del Pedido de Compra</label>
                    <input type="number" id="idPedido" class="form-control" placeholder="Ingrese el ID del Pedido (ej: 1, 2, 3)" min="1" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" class="btn btn-primary-custom w-100" id="buscarPedidoBtn">
                        <i class="bi bi-search"></i> Buscar Pedido
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="formRecepcion" style="display: none;">
        <input type="hidden" name="idPedidoHidden" id="idPedidoHidden">
        
        <div class="card mb-4 shadow">
            <div class="card-header card-header-custom">Información del Pedido</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID Pedido:</strong> <span id="info-id-pedido"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Proveedor:</strong> <span id="info-proveedor"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Fecha Creación:</strong> <span id="info-fecha-creacion"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow">
            <div class="card-header card-header-custom">Productos Recibidos</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID Detalle</th>
                                <th>Producto</th>
                                <th>Cant. Pedida</th>
                                <th>Cant. Recibida <span class="text-danger">*</span></th>
                                <th>Costo Unit. Real <span class="text-danger">*</span></th>
                                <th>Fecha Venc. (Opcional)</th>
                            </tr>
                        </thead>
                        <tbody id="tablaRecepcionBody">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success-custom btn-lg w-100 mt-3" id="procesarRecepcionBtn">
            <i class="bi bi-box-arrow-in-down"></i> Procesar Recepción e Ingresar Stock
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscarPedidoBtn = document.getElementById('buscarPedidoBtn');
    const idPedidoInput = document.getElementById('idPedido');
    const formRecepcion = document.getElementById('formRecepcion');
    const tablaRecepcionBody = document.getElementById('tablaRecepcionBody');
    const alertMessages = document.getElementById('alert-messages');
    const base_url = "<?= $base_url ?>";

    function showMessage(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        `;
        alertMessages.innerHTML = alertHtml;
    }

    function buildTable(detalles) {
        let html = '';
        detalles.forEach((item) => {
            html += `
                <tr>
                    <td>${item.idDetalle}</td>
                    <td>${item.nombreProducto}</td>
                    <td>${item.cantidadPedida}</td>
                    <td>
                        <input type="number" 
                               name="productos[${item.idDetalle}][cantidad]" 
                               class="form-control" 
                               value="${item.cantidadPedida}" 
                               min="0"
                               required>
                        <input type="hidden" name="productos[${item.idDetalle}][idDetalle]" value="${item.idDetalle}">
                    </td>
                    <td>
                        <input type="number" 
                               name="productos[${item.idDetalle}][costoUnitario]" 
                               class="form-control" 
                               value="${item.costoUnitario || 0.01}" 
                               min="0.01"
                               step="0.01"
                               required>
                    </td>
                    <td>
                        <input type="date" 
                               name="productos[${item.idDetalle}][fechaVencimiento]" 
                               class="form-control">
                    </td>
                </tr>
            `;
        });
        tablaRecepcionBody.innerHTML = html;
    }

    buscarPedidoBtn.addEventListener('click', function() {
        const idPedido = idPedidoInput.value.trim();
        if (!idPedido) {
            showMessage('danger', 'Por favor, ingrese el ID del Pedido.');
            return;
        }

        fetch(`${base_url}index.php?c=recepcion&a=buscarPedido&id=${idPedido}`)
            .then(response => response.json())
            .then(result => {
                alertMessages.innerHTML = '';
                if (result.success) {
                    const data = result.data;
                    const primerItem = data[0];

                    document.getElementById('info-id-pedido').textContent = primerItem.idPedido;
                    document.getElementById('info-proveedor').textContent = primerItem.nombreProveedor;
                    document.getElementById('info-fecha-creacion').textContent = primerItem.fechaCreacion.substring(0, 10);
                    document.getElementById('idPedidoHidden').value = primerItem.idPedido;

                    buildTable(data);
                    formRecepcion.style.display = 'block';
                    showMessage('success', `Pedido #${primerItem.idPedido} cargado correctamente. Verifique las cantidades recibidas.`);
                } else {
                    formRecepcion.style.display = 'none';
                    showMessage('danger', result.message || 'Error al buscar el pedido.');
                }
            })
            .catch(error => {
                formRecepcion.style.display = 'none';
                showMessage('danger', 'Error de conexión al buscar el pedido.');
                console.error('Error:', error);
            });
    });

    formRecepcion.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const idPedido = document.getElementById('idPedidoHidden').value;
        const formData = new FormData(formRecepcion);
        const productos = [];
        
        // Recolectar y validar datos de la tabla
        let allValid = true;
        const inputElements = tablaRecepcionBody.querySelectorAll('input[type="number"]');
        
        inputElements.forEach(input => {
            if (input.value <= 0 && input.name.includes('[cantidad]')) {
                // Si la cantidad es 0, el producto no se recibe y no causa error, pero se verifica la validez.
            } else if (input.value <= 0 && input.name.includes('[costoUnitario]')) {
                allValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        if (!allValid) {
            showMessage('danger', 'El costo unitario real debe ser mayor que cero para los productos recibidos.');
            return;
        }

        // Serialización manual de los productos
        const detalles = {};
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('productos[')) {
                // key = productos[idDetalle][campo]
                const match = key.match(/productos\[(\d+)\]\[(\w+)\]/);
                if (match) {
                    const idDetalle = match[1];
                    const campo = match[2];
                    if (!detalles[idDetalle]) {
                        detalles[idDetalle] = {};
                    }
                    detalles[idDetalle][campo] = value;
                }
            }
        }
        
        for (const idDetalle in detalles) {
            if (detalles[idDetalle].cantidad > 0) {
                 productos.push({
                    idDetalle: parseInt(idDetalle),
                    cantidad: parseInt(detalles[idDetalle].cantidad),
                    costoUnitario: parseFloat(detalles[idDetalle].costoUnitario),
                    fechaVencimiento: detalles[idDetalle].fechaVencimiento || null
                });
            }
        }

        if (productos.length === 0) {
            showMessage('danger', 'Debe ingresar al menos un producto con una cantidad recibida mayor a cero.');
            return;
        }

        const dataToSend = {
            idPedido: idPedido,
            productos: productos
        };

        fetch(`${base_url}index.php?c=recepcion&a=procesarRecepcion`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dataToSend)
        })
        .then(response => response.json())
        .then(result => {
            alertMessages.innerHTML = '';
            if (result.success) {
                showMessage('success', result.message);
                formRecepcion.style.display = 'none';
                idPedidoInput.value = '';
            } else {
                showMessage('danger', result.message || 'Error desconocido al procesar la recepción.');
            }
        })
        .catch(error => {
            showMessage('danger', 'Error de conexión al procesar la recepción.');
            console.error('Error:', error);
        });
    });
});
</script>
</body>
</html>