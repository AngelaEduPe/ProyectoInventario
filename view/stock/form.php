<?php include(__DIR__ . "/../BarraMenu.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .content {
            margin-left: 270px;
            padding: 20px;
        }
        .card-header {
            background-color: #7245da;
            color: white;
            padding: 1.5rem;
            border-bottom: 3px solid #5a35b0;
        }
        .card-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .card-subtitle {
            font-size: 1rem;
            font-style: italic;
            opacity: 0.8;
        }
        .list-group-item:hover {
            cursor: pointer;
            background-color: #f8f9fa;
        }
        .selected-product-info {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            padding: .5rem .75rem;
            margin-top: .5rem;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="card shadow-lg mb-4 rounded-3">
            <div class="card-header rounded-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="card-title">Agregar Stock</h1>
                        <p class="card-subtitle">Completa el formulario para ingresar nuevas existencias.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-lg mb-4 rounded-3 p-4">
            <form action="index.php?c=stock&a=insertar" method="POST">
                <?php
                if (isset($_GET['msg'])) {
                    if ($_GET['msg'] === 'success') {
                        echo '<div class="alert alert-success">¡Stock agregado correctamente!</div>';
                    } else if ($_GET['msg'] === 'error') {
                        echo '<div class="alert alert-danger">Hubo un error al agregar el stock.</div>';
                    }
                }
                ?>

                <div class="mb-3">
                    <label for="producto-input" class="form-label fw-bold">Buscar Producto:</label>
                    <input type="text" class="form-control" id="producto-input" placeholder="Escribe el nombre o SKU del producto" autocomplete="off">
                    <input type="hidden" name="idProducto" id="idProducto" required>
                    <div id="productos-lista" class="list-group mt-2" style="max-height: 200px; overflow-y: auto;"></div>
                    <div id="selected-product" class="selected-product-info mt-2" style="display: none;"></div>
                </div>
                
                <div class="mb-3">
                    <label for="cantidad" class="form-label fw-bold">Cantidad:</label>
                    <input type="number" class="form-control" name="cantidad" id="cantidad" required min="1">
                </div>
                
                <div class="mb-3">
                    <label for="fechaVencimiento" class="form-label fw-bold">Fecha de Vencimiento (opcional):</label>
                    <input type="date" class="form-control" name="fechaVencimiento" id="fechaVencimiento">
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-plus-circle me-2"></i> Agregar Stock
                </button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let timeout = null;

            $('#producto-input').on('input', function() {
                clearTimeout(timeout);
                const termino = $(this).val();
                
                if (termino.length < 3) {
                    $('#productos-lista').html('');
                    $('#idProducto').val('');
                    $('#selected-product').hide();
                    return;
                }

                timeout = setTimeout(() => {
                    $.ajax({
                        url: `index.php?c=stock&a=buscar&termino=${termino}`,
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#productos-lista').html('');
                            if (data.length > 0) {
                                $.each(data, function(index, producto) {
                                    const item = $('<a>')
                                        .attr('href', '#')
                                        .addClass('list-group-item list-group-item-action')
                                        .text(`${producto.nombre} (SKU: ${producto.sku})`)
                                        .on('click', function(e) {
                                            e.preventDefault();
                                            $('#producto-input').val(''); 
                                            $('#idProducto').val(producto.idProducto);
                                            $('#productos-lista').html('');
                                            $('#selected-product').html(`<strong>Producto seleccionado:</strong> ${producto.nombre} (SKU: ${producto.sku})`).show();
                                        });
                                    $('#productos-lista').append(item);
                                });
                            } else {
                                $('#productos-lista').html('<div class="list-group-item">No se encontraron productos.</div>');
                            }
                        }
                    });
                }, 500);
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>