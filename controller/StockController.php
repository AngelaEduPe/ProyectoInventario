<?php
require_once __DIR__ . '/../model/Producto.php';
require_once __DIR__ . '/../config/Conexion.php';

class StockController {
    private $productoModel;

    public function __construct() {
        $this->productoModel = new Producto();
    }

    public function form() {
        require_once __DIR__ . '/../view/stock/form.php';
    }

    public function buscar() {
        header('Content-Type: application/json');
        if (isset($_GET['termino'])) {
            $termino = $_GET['termino'];
            $productos = $this->productoModel->buscarProductos($termino);
            echo json_encode($productos);
        } else {
            echo json_encode([]);
        }
    }

    public function insertar() {
        if (isset($_POST['idProducto']) && isset($_POST['cantidad'])) {
            $idProducto = $_POST['idProducto'];
            $cantidad = $_POST['cantidad'];
            $fechaVencimiento = $_POST['fechaVencimiento'] ?: null;
            $usuario = $_SESSION['usuario'] ?? 'sistema';

            if ($this->productoModel->insertarStock($idProducto, $cantidad, $fechaVencimiento, $usuario)) {
                header('Location: index.php?c=reporte&a=stock&msg=success');
            } else {
                header('Location: index.php?c=reporte&a=stock&msg=error');
            }
            exit();
        }
    }
}