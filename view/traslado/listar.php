<?php
include(__DIR__ . '/../BarraMenu.php');

$tiendas = $tiendas ?? []; 

$base_url = "http://localhost/TPWEB/ProyectoInventario/";
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Traslado</title>
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

        .btn-delete {
            background-color: #ec4899;
            border: none;
            color: #fff;
            transition: 0.3s;
        }
        .btn-delete:hover {
            background-color: #db2777;
            color: #fff;
            transform: scale(1.05);
        }
        
        .card-header-custom {
            background: linear-gradient(90deg, #6d28d9, #8b5cf6);
            color: white;
            font-weight: 600;
            border-bottom: none;
        }
        
        table thead {
            background: #6d28d9 !important;
            color: #fff;
        }

        .list-group-item:hover {
            cursor: pointer;
            background-color: rgba(139, 92, 246, 0.1);
        }
        
        .selected-product-info {
            background-color: rgba(139, 92, 246, 0.1);
            border: 1px solid #8b5cf6;
            border-radius: .5rem;
            padding: .75rem;
            margin-top: .5rem;
        }
    </style>
</head>
<body>
<div class="content container mt-5">
    <h1 class="mb-4"><i class="bi bi-truck"></i> Registrar Traslado de Stock</h1>
    <p class="text-muted">Documente la salida de inventario hacia otra Tienda/Ubicación.</p>

    <?php if ($msg === 'error_traslado'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Error: No se pudo procesar el traslado. Verifique que el **stock sea suficiente**.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php elseif ($msg === 'success_traslado'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> ¡Traslado registrado con éxito!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <form id="formTraslado" method="POST" action="<?= $base_url ?>index.php?c=traslado&a=registrarNuevoTraslado">
        
        <div class="card mb-4 shadow">
            <div class="card-header card-header-custom">Destino y Detalles</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="destino" class="form-label">Ubicación de Destino (Tienda) <span class="text-danger">*</span></label>
                        <select id="destino" name="destino" class="form-select" required>
                            <option value="">Seleccione el destino</option>
                            <?php if (!empty($tiendas)):
                                foreach ($tiendas as $tienda): ?>
                                    <option value="<?= htmlspecialchars($tienda['idTienda']) ?>">
                                        <?= htmlspecialchars($tienda['nombre']) ?>
                                    </option>
                                <?php endforeach;
                            endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="observacion" class="form-label">Observación / Motivo</label>
                        <input type="text" id="observacion" name="observacion" class="form-control" maxlength="255" placeholder="Ej: Movimiento por reposición de stock">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow">
            <div class="card-header card-header-custom">Productos a Despachar</div>
            <div class="card-body">
                
                <div class="row mb-4 align-items-end">
                    <div class="col-md-7">
                        <label for="buscarProducto" class="form-label">Buscar Producto por Nombre/Código</label>
                        <input type="text" id="buscarProducto" class="form-control" placeholder="Escriba el término a buscar y presione Buscar" autocomplete="off">
                        <div id="producto-seleccionado-info" class="selected-product-info" style="display: none;"></div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-lila w-100" id="buscarProductoBtn">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-lila w-100" id="agregarProductoBtn" disabled>
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
                                <th>Cantidad a Trasladar <span class="text-danger">*</span></th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tablaDetallesBody">
                            </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-center text-muted" id="mensajeVacio">Aún no hay productos seleccionados.</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-lila btn-lg w-100 mt-3"><i class="bi bi-check-circle"></i> Confirmar y Registrar Salida</button>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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

    function seleccionarProducto(producto) {
        productoSeleccionado = { 
            id: parseInt(producto.idProducto), 
            nombre: producto.nombre, 
            sku: producto.sku,
            stock: parseFloat(producto.stockTotal)
        };
        
        productoSeleccionadoInfo.innerHTML = `<strong>Producto seleccionado:</strong> ${producto.nombre} (SKU: ${producto.sku}) | Stock: ${producto.stockTotal}`;
        productoSeleccionadoInfo.style.display = 'block';
        
        sugerenciasLista.innerHTML = '';
        agregarProductoBtn.disabled = false;
        buscarProductoInput.value = ''; // Limpiar el input para nuevas búsquedas
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

        // 1. Llamada AJAX a StockController::buscar()
        fetch(`${base_url}index.php?c=stock&a=buscar&termino=${termino}`)
            .then(response => response.json())
            .then(productos => {
                
                let count = 0;
                
                productos.forEach(p => {
                    const stock = parseFloat(p.stockTotal || 0);

                    // 2. Solo listar productos que tienen stock y que no han sido añadidos
                    if (stock > 0 && !productosSeleccionados.has(parseInt(p.idProducto))) {
                        const item = document.createElement('a');
                        item.href = '#';
                        item.classList.add('list-group-item', 'list-group-item-action');
                        item.innerHTML = `<strong>${p.nombre}</strong> (SKU: ${p.sku}) | Stock: <span class="badge bg-success">${stock}</span>`;
                        
                        // 3. Al hacer click, selecciona el producto
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            seleccionarProducto(p);
                        });
                        
                        sugerenciasLista.appendChild(item);
                        count++;
                    }
                });

                if (count === 0) {
                    sugerenciasLista.innerHTML = '<div class="list-group-item text-muted">No se encontraron productos con stock disponible que no estén ya en la lista.</div>';
                }
            })
            .catch(error => {
                sugerenciasLista.innerHTML = '<div class="list-group-item text-danger">Error al buscar productos. Revise la consola.</div>';
                console.error('Error al buscar producto:', error);
            });
    }); 

    agregarProductoBtn.addEventListener('click', function() {
        if (productoSeleccionado) {
            
            // 4. Añadir a la tabla
            productosSeleccionados.set(productoSeleccionado.id, productoSeleccionado);
            
            const newRow = document.createElement('tr');
            newRow.dataset.idProducto = productoSeleccionado.id;
            
            newRow.innerHTML = `
                <td>${productoSeleccionado.id}</td>
                <td>${productoSeleccionado.nombre}
                    <input type="hidden" name="productos[${productoSeleccionado.id}][idProducto]" value="${productoSeleccionado.id}">
                </td>
                <td><span class="badge bg-info">${productoSeleccionado.stock}</span></td>
                <td>
                    <input type="number" 
                            name="productos[${productoSeleccionado.id}][cantidad]" 
                            class="form-control cantidad-input" 
                            required 
                            min="1" 
                            max="${productoSeleccionado.stock}"
                            placeholder="Máx ${productoSeleccionado.stock}"
                            data-max="${productoSeleccionado.stock}">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-delete btn-sm eliminar-producto" title="Eliminar"><i class="bi bi-trash"></i></button>
                </td>
            `;
            
            tablaDetallesBody.appendChild(newRow);
            mensajeVacio.style.display = 'none';
            
            // 5. Resetear estado
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
            
            if (tablaDetallesBody.children.length === 0) {
                mensajeVacio.style.display = 'table-row';
            }
        }
    });

    tablaDetallesBody.addEventListener('change', function(e) {
        if (e.target.classList.contains('cantidad-input')) {
            const input = e.target;
            const cantidad = parseInt(input.value);
            const maxStock = parseInt(input.dataset.max);

            if (cantidad > maxStock) {
                alert(`Error: La cantidad a trasladar (${cantidad}) excede el stock actual (${maxStock}).`);
                input.value = maxStock;
            }
            if (cantidad <= 0 || isNaN(cantidad)) {
                input.value = 1;
            }
        }
    });
});
</script>
</body>
</html>