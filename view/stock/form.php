<?php
include(__DIR__ . '/../BarraMenu.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Stock</title>
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
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Agregar Stock</h1>
                <p class="text-muted">Completa el formulario para ingresar nuevas existencias.</p>
            </div>
        </div>

        <div class="p-4 rounded-3 bg-white shadow">
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
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-lila btn-lg w-100">
                    <i class="bi bi-plus-circle me-2"></i> Agregar Stock
                </button>

                <a href="index.php?c=reporte&a=stock" class="btn btn-secondary btn-lg w-100">
                    <i class="bi bi-x-circle me-2"></i> Cancelar
                </a>
            </div>
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
