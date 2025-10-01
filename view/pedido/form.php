<?php
include(__DIR__ . '/../BarraMenu.php');

$base_url = "http://localhost/TPWEB/ProyectoInventario/";
$msg = $_GET['msg'] ?? '';
$proveedores = $proveedores ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Pedido de Compra</title>
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
            color: #007bff;
        }

        .btn-primary-custom {
            background-color: #007bff;
            border: none;
            color: #fff;
            transition: 0.3s;
        }
        .btn-primary-custom:hover {
            background-color: #0056b3;
            color: #fff;
            transform: scale(1.05);
        }
        
        .card-header-custom {
            background: linear-gradient(90deg, #007bff, #00c6ff);
            color: white;
            font-weight: 600;
            border-bottom: none;
        }
        
        table thead {
            background: #007bff !important;
            color: #fff;
        }

        .list-group-item:hover {
            cursor: pointer;
            background-color: rgba(0, 123, 255, 0.1);
        }
        
        .selected-product-info {
            background-color: rgba(0, 123, 255, 0.1);
            border: 1px solid #007bff;
            border-radius: .5rem;
            padding: .75rem;
            margin-top: .5rem;
        }
    </style>
</head>
<body>
<div class="content container mt-5">
    <h1 class="mb-4"><i class="bi bi-cart-plus"></i> Registrar Pedido de Compra</h1>
    <p class="text-muted">Documente el pedido de productos a un proveedor.</p>

    <?php if ($msg === 'error_registro'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Error: No se pudo registrar el pedido.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php elseif ($msg === 'success_registro_pedido'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> ¡Pedido registrado con éxito!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <form id="formPedido" method="POST" action="<?= $base_url ?>index.php?c=pedido&a=registrar">
        
        <div class="card mb-4 shadow">
            <div class="card-header card-header-custom">Proveedor</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="idProveedor" class="form-label">Seleccione Proveedor <span class="text-danger">*</span></label>
                        <select id="idProveedor" name="idProveedor" class="form-select" required>
                            <option value="">Seleccione el proveedor</option>
                            <?php foreach ($proveedores as $p): ?>
                                <option value="<?= $p['idProveedor'] ?>">
                                    <?= $p['razonSocial'] ?> (<?= $p['numeroDocumento'] ?>) 
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow">
            <div class="card-header card-header-custom">Productos del Pedido</div>
            <div class="card-body">
                
                <div class="row mb-4 align-items-end">
                    <div class="col-md-7">
                        <label for="buscarProducto" class="form-label">Buscar Producto por Nombre/Código</label>
                        <input type="text" id="buscarProducto" class="form-control" placeholder="Escriba el término a buscar y presione Buscar" autocomplete="off">
                        <div id="producto-seleccionado-info" class="selected-product-info" style="display: none;"></div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary-custom w-100" id="buscarProductoBtn">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary-custom w-100" id="agregarProductoBtn" disabled>
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
                                <th>Cantidad <span class="text-danger">*</span></th>
                                <th>Costo Unitario <span class="text-danger">*</span></th>
                                <th>Fecha Venc. (Opcional)</th>
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
        
        <button type="submit" class="btn btn-primary-custom btn-lg w-100 mt-3"><i class="bi bi-send"></i> Generar Pedido</button>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formPedido = document.getElementById('formPedido');
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
            sku: producto.sku
        };
        
        productoSeleccionadoInfo.innerHTML = `<strong>Producto seleccionado:</strong> ${producto.nombre} (SKU: ${producto.sku})`;
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
                    if (!productosSeleccionados.has(parseInt(p.idProducto))) {
                        const item = document.createElement('a');
                        item.href = '#';
                        item.classList.add('list-group-item', 'list-group-item-action');
                        item.innerHTML = `<strong>${p.nombre}</strong> (SKU: ${p.sku})`;
                        
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
                <td>
                    <input type="number" 
                                name="productos[${productoSeleccionado.id}][cantidad]" 
                                class="form-control cantidad-input" 
                                required 
                                min="1" 
                                value="1">
                </td>
                <td>
                    <input type="number" 
                                name="productos[${productoSeleccionado.id}][costo]" 
                                class="form-control costo-input" 
                                required 
                                min="0.01"
                                step="0.01"
                                value="0.01">
                </td>
                <td>
                    <input type="date" 
                                name="productos[${productoSeleccionado.id}][fechaVencimiento]" 
                                class="form-control">
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
    
    formPedido.addEventListener('submit', function(e) {
        if (tablaDetallesBody.children.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un producto al pedido.');
            return false;
        }
        
        if (document.getElementById('idProveedor').value === '') {
            e.preventDefault();
            alert('Debe seleccionar un proveedor.');
            return false;
        }
    });
    
    actualizarMensajeVacio();
});
</script>
</body>
</html>