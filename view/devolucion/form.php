<?php
include(__DIR__ . '/../BarraMenu.php');

$base_url = "http://localhost/TPWEB/ProyectoInventario/";
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Devolución</title>
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
            color: #198754; 
        }

        .btn-success-custom {
            background-color: #198754;
            border: none;
            color: #fff;
            transition: 0.3s;
        }
        .btn-success-custom:hover {
            background-color: #157347;
            color: #fff;
            transform: scale(1.05);
        }
        
        .card-header-custom {
            background: linear-gradient(90deg, #198754, #48bb78); 
            color: white;
            font-weight: 600;
            border-bottom: none;
        }
        
        table thead {
            background: #198754 !important; 
            color: #fff;
        }

        .list-group-item:hover {
            cursor: pointer;
            background-color: rgba(25, 135, 84, 0.1); 
        }
        
        .selected-product-info {
            background-color: rgba(25, 135, 84, 0.1); 
            border: 1px solid #198754;
            border-radius: .5rem;
            padding: .75rem;
            margin-top: .5rem;
        }
    </style>
</head>
<body>
<div class="content container mt-5">
    <h1 class="mb-4"><i class="bi bi-arrow-left-right"></i> Registrar Devolución de Inventario</h1>
    <p class="text-muted">Registre la entrada de productos devueltos por clientes o proveedores.</p>

    <?php if ($msg === 'error_devolucion'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Error: No se pudo procesar la devolución.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php elseif ($msg === 'success_devolucion'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> ¡Devolución registrada con éxito!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <form id="formDevolucion" method="POST" action="<?= $base_url ?>index.php?c=devolucion&a=registrarNuevaDevolucion">
        
        <div class="card mb-4 shadow">
            <div class="card-header card-header-custom">Datos de la Devolución</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="documentoReferencia" class="form-label">Doc. Referencia (Factura/Boleta)</label>
                        <input type="text" id="documentoReferencia" name="documentoReferencia" class="form-control" placeholder="Ej: F001-000123" maxlength="50">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="motivo" class="form-label">Motivo de Devolución <span class="text-danger">*</span></label>
                        <select id="motivo" name="motivo" class="form-select" required>
                            <option value="">Seleccione el motivo</option>
                            <option value="Devolución por cliente (Cambio)">Cliente - Cambio</option>
                            <option value="Devolución por cliente (Garantía)">Cliente - Garantía</option>
                            <option value="Error de envío/Recepción">Error de Logística</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow">
            <div class="card-header card-header-custom">Productos Devueltos (Ingreso a Stock)</div>
            <div class="card-body">
                
                <div class="row mb-4 align-items-end">
                    <div class="col-md-7">
                        <label for="buscarProducto" class="form-label">Buscar Producto por Nombre/Código</label>
                        <input type="text" id="buscarProducto" class="form-control" placeholder="Escriba el término a buscar y presione Buscar" autocomplete="off">
                        <div id="producto-seleccionado-info" class="selected-product-info" style="display: none;"></div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success-custom w-100" id="buscarProductoBtn">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success-custom w-100" id="agregarProductoBtn" disabled>
                            <i class="bi bi-plus-circle"></i> Añadir
                        </button>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div id="productos-sugerencias" class="list-group" style="max-height: 250px; overflow-y: auto;"></div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>Stock Actual</th>
                                <th>Cantidad a Devolver <span class="text-danger">*</span></th>
                                <th class="text-center">Observación</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tablaDetallesBody">
                            </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-center text-muted" id="mensajeVacio">Aún no hay productos seleccionados.</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success-custom btn-lg w-100 mt-3"><i class="bi bi-check-circle"></i> Confirmar y Registrar Devolución</button>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formDevolucion = document.getElementById('formDevolucion');
    const buscarProductoInput = document.getElementById('buscarProducto');
    const buscarProductoBtn = document.getElementById('buscarProductoBtn');
    const sugerenciasLista = document.getElementById('productos-sugerencias');
    const productoSeleccionadoInfo = document.getElementById('producto-seleccionado-info');
    const agregarProductoBtn = document.getElementById('agregarProductoBtn');
    const tablaDetallesBody = document.getElementById('tablaDetallesBody');
    const mensajeVacio = document.getElementById('mensajeVacio');
    const base_url = "<?= $base_url ?>";
    let productosSeleccionados = new Map();
    let productoSeleccionado = null; 

    function actualizarMensajeVacio() {
        if (tablaDetallesBody.children.length === 0) {
            mensajeVacio.style.display = 'table-row';
        } else {
            mensajeVacio.style.display = 'none';
        }
    }

    function seleccionarProducto(producto) {
        productoSeleccionado = { 
            id: parseInt(producto.idProducto), 
            nombre: producto.nombre, 
            sku: producto.sku,
            stock: parseFloat(producto.stockTotal || 0)
        };
        
        productoSeleccionadoInfo.innerHTML = `<strong>Producto seleccionado:</strong> ${producto.nombre} (SKU: ${producto.sku}) | Stock Actual: ${producto.stockTotal}`;
        productoSeleccionadoInfo.style.display = 'block';
        
        sugerenciasLista.innerHTML = '';
        agregarProductoBtn.disabled = false;
        buscarProductoInput.value = '';
        buscarProductoInput.focus();
    }

    buscarProductoBtn.addEventListener('click', function() {
        const termino = buscarProductoInput.value.trim();
        
        productoSeleccionado = null;
        agregarProductoBtn.disabled = true;
        sugerenciasLista.innerHTML = '';
        productoSeleccionadoInfo.style.display = 'none';

        if (termino.length < 3) {
            sugerenciasLista.innerHTML = '<div class="list-group-item text-danger">Debe escribir al menos 3 caracteres para buscar.</div>';
            return;
        }
        
        fetch(`${base_url}index.php?c=stock&a=buscar&termino=${termino}`)
            .then(response => response.json())
            .then(productos => {
                
                let count = 0;
                sugerenciasLista.innerHTML = '';
                
                productos.forEach(p => {
                    const stock = parseFloat(p.stockTotal || 0);
                    
                    if (!productosSeleccionados.has(parseInt(p.idProducto))) {
                        const item = document.createElement('a');
                        item.href = '#';
                        item.classList.add('list-group-item', 'list-group-item-action');
                        item.innerHTML = `<strong>${p.nombre}</strong> (SKU: ${p.sku}) | Stock Actual: <span class="badge bg-info">${stock}</span>`;
                        
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            seleccionarProducto(p);
                        });
                        
                        sugerenciasLista.appendChild(item);
                        count++;
                    }
                });

                if (count === 0) {
                    sugerenciasLista.innerHTML = '<div class="list-group-item text-muted">No se encontraron productos disponibles que no estén ya en la lista.</div>';
                }
            })
            .catch(error => {
                sugerenciasLista.innerHTML = '<div class="list-group-item text-danger">Error al buscar productos. Revise la consola.</div>';
                console.error('Error al buscar producto:', error);
            });
    }); 

    agregarProductoBtn.addEventListener('click', function() {
        if (productoSeleccionado) {
            
            productosSeleccionados.set(productoSeleccionado.id, productoSeleccionado);
            
            const newRow = document.createElement('tr');
            newRow.dataset.idProducto = productoSeleccionado.id;
            
            newRow.innerHTML = `
                <td>${productoSeleccionado.id}</td>
                <td>${productoSeleccionado.nombre}
                    <input type="hidden" name="productos[${productoSeleccionado.id}][id]" value="${productoSeleccionado.id}">
                </td>
                <td><span class="badge bg-info">${productoSeleccionado.stock}</span></td>
                <td>
                    <input type="number" 
                                name="productos[${productoSeleccionado.id}][cantidad]" 
                                class="form-control cantidad-input" 
                                required 
                                min="1" 
                                value="1"
                                placeholder="Cantidad a ingresar">
                </td>
                <td>
                    <input type="text" 
                                name="productos[${productoSeleccionado.id}][observacion]" 
                                class="form-control"
                                maxlength="255"
                                placeholder="Condición del producto">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm eliminar-producto" title="Eliminar"><i class="bi bi-trash"></i></button>
                </td>
            `;
            
            tablaDetallesBody.appendChild(newRow);
            actualizarMensajeVacio();
            
            productoSeleccionadoInfo.style.display = 'none';
            buscarProductoInput.placeholder = 'Escriba el término a buscar y presione Buscar';
            productoSeleccionado = null;
            agregarProductoBtn.disabled = true;
        } 
    });

    tablaDetallesBody.addEventListener('click', function(e) {
        if (e.target.closest('.eliminar-producto')) {
            const row = e.target.closest('tr');
            const idProducto = parseInt(row.dataset.idProducto);
            
            productosSeleccionados.delete(idProducto);
            row.remove();
            
            actualizarMensajeVacio();
        }
    });

    tablaDetallesBody.addEventListener('change', function(e) {
        if (e.target.classList.contains('cantidad-input')) {
            const input = e.target;
            const cantidad = parseInt(input.value);

            if (cantidad <= 0 || isNaN(cantidad)) {
                alert('La cantidad a devolver debe ser al menos 1.');
                input.value = 1;
            }
        }
    });
    
    formDevolucion.addEventListener('submit', function(e) {
        if (tablaDetallesBody.children.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un producto a la devolución.');
            return false;
        }
    });
    
    actualizarMensajeVacio();
});
</script>
</body>
</html>