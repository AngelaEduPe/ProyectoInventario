<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rolUsuario = $_SESSION['nombreRol'] ?? '';
$base_url = "http://localhost/TPWEB/ProyectoInventario/";
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
    }
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 250px;
        background: linear-gradient(180deg, #7245daff, #452183ff);
        color: white;
        padding-top: 20px;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 10px rgba(0,0,0,0.3);
    }
    .sidebar h4 {
        text-align: center;
        font-weight: 600;
        margin-bottom: 30px;
        font-size: 20px;
        letter-spacing: 1px;
    }
    .sidebar a {
        display: flex;
        align-items: center;
        padding: 10px 20px;
        color: white;
        text-decoration: none;
        transition: 0.3s;
        font-weight: 500;
    }

    .sidebar a i {
        margin-right: 10px;
        font-size: 18px;
    }
    .sidebar a:hover {
        background: rgba(255, 255, 255, 0.15);
        padding-left: 25px;
        border-radius: 8px;
    }
    .sidebar .subtitle {
        margin: 20px 0 10px 20px;
        font-size: 13px;
        font-weight: bold;
        text-transform: uppercase;
        color: #d1c4e9;
        letter-spacing: 0.5px;
    }
    .logout {
        margin-top: auto;
    }
    .content {
        margin-left: 250px;
        padding: 20px;
    }
</style>

<div class="sidebar">
    <h4><i class="bi bi-brush"></i> My reflection</h4>
    
    <a href="<?= $base_url ?>index.php?c=dashboard&a=index"><i class="bi bi-speedometer2"></i> Dashboard</a>
    
    <div class="subtitle">Inventario</div>
    <a href="<?= $base_url ?>index.php?c=categoria&a=listar"><i class="bi bi-tags"></i> Categorías</a>
    <a href="<?= $base_url ?>index.php?c=subcategoria&a=listar"><i class="bi bi-tag"></i> Subcategorías</a>
    <a href="<?= $base_url ?>index.php?c=marca&a=listar"><i class="bi bi-bookmark"></i> Marcas</a>
    <a href="<?= $base_url ?>index.php?c=producto&a=listar"><i class="bi bi-box-seam"></i> Productos</a>
    
    <div class="subtitle">Movimientos</div>
    <a href="<?= $base_url ?>index.php?c=compra&a=listar"><i class="bi bi-cart4"></i> Compras</a>
    <a href="<?= $base_url ?>index.php?c=recibido&a=listar"><i class="bi bi-basket3"></i> Recibidos</a>
    
    <div class="subtitle">Reportes</div>
    <a href="<?= $base_url ?>index.php?c=reporte&a=stock"><i class="bi bi-graph-up-arrow"></i> Reporte de Stock</a>
    
    <?php if ($rolUsuario === 'Administrador'): ?>
        <div class="subtitle">Mantenimiento</div>
        <a href="<?= $base_url ?>index.php?c=proveedor&a=listar"><i class="bbi bi-truck"></i> Proveedores</a>
        <a href="<?= $base_url ?>index.php?c=usuario&a=listar"><i class="bi bi-people-fill"></i> Usuarios</a>
    <?php endif; ?>
    
    <a href="<?= $base_url ?>index.php?logout=1" class="logout"><i class="bi bi-box-arrow-left"></i> Cerrar sesión</a>
</div>