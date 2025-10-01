<?php
require_once __DIR__ . '/../model/Stock.php';
require_once __DIR__ . '/../config/Conexion.php';

class MovimientoController {
    private $stockModel;

    public function __construct() {
        $this->stockModel = new Stock();
    }

    public function listar() {
        $movimientos = $this->stockModel->obtenerMovimientos();
        require_once __DIR__ . '/../view/movimiento/listar.php';
    }
}