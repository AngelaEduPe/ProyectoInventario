<?php
require_once __DIR__ . '/../model/Producto.php';

class ReporteController {
    private $productoModel;

    public function __construct() {
        $this->productoModel = new Producto();
    }

    public function stock() {
        $data = $this->productoModel->obtenerReporteStock();
        require_once __DIR__ . '/../view/reporte/stock.php';
    }
}