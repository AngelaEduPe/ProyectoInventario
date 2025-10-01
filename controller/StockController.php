<?php
require_once __DIR__ . '/../model/Producto.php';
require_once __DIR__ . '/../model/Stock.php';
require_once __DIR__ . '/../config/Conexion.php';

class StockController {
    private $productoModel;
    private $stockModel;
    
    private const COSTO_AJUSTE_MANUAL = 0; 
    private const ID_TIPO_AJUSTE_ENTRADA = 5;

    public function __construct() {
        $this->productoModel = new Producto();
        $this->stockModel = new Stock();
    }

    public function form() {
        require_once __DIR__ . '/../view/stock/form.php';
    }

    public function buscar() {
        header('Content-Type: application/json');
        if (isset($_GET['termino'])) {
            $termino = $_GET['termino'];
            $productos = $this->stockModel->buscarProductosConStock($termino);
            
            die(json_encode($productos));
        } else {
            die(json_encode([]));
        }
    }

    public function insertar() {
        if (isset($_POST['idProducto']) && isset($_POST['cantidad'])) {
            
            $idProducto = $_POST['idProducto'];
            $cantidad = (int)$_POST['cantidad'];
            $fechaVencimiento = $_POST['fechaVencimiento'] ?: null; 
            
            $usuario = $_SESSION['usuario'] ?? 'Sistema';
            $idUsuario = $_SESSION['idUsuario'] ?? 1;
            
            $idDocumentoReferencia = null; 
            
            $productos = [
                [
                    'id' => $idProducto,
                    'cantidad' => $cantidad,
                    'costo' => self::COSTO_AJUSTE_MANUAL, 
                    'fechaVencimiento' => $fechaVencimiento
                ]
            ];

            try {
                if ($this->stockModel->procesarEntradaStock(
                    self::ID_TIPO_AJUSTE_ENTRADA, 
                    $idUsuario,                   
                    $idDocumentoReferencia,       
                    $productos,                   
                    $usuario                      
                )) {
                    header('Location: index.php?c=reporte&a=stock&msg=success_ajuste_entrada');
                } else {
                    throw new Exception("El modelo de stock no retornó éxito (false)."); 
                }
            } catch (Exception $e) {
                error_log("Error en ajuste manual de stock: " . $e->getMessage());
                header('Location: index.php?c=stock&a=form&msg=error_ajuste');
            }
        } else {
             header('Location: index.php?c=stock&a=form&msg=error_data');
        }
        exit();
    }
}