<?php
require_once __DIR__ . '/../model/Devolucion.php';
require_once __DIR__ . '/../model/Stock.php';
require_once __DIR__ . '/../config/Conexion.php';

class DevolucionController {
    private $devolucionModel;
    private $stockModel;

    private const ID_TIPO_DEVOLUCION = 2; 

    public function __construct() {
        $this->devolucionModel = new Devolucion();
        $this->stockModel = new Stock();
    }

    public function form() {
        require_once __DIR__ . '/../view/devolucion/form.php';
    }

    public function registrarNuevaDevolucion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=devolucion&a=form');
            return;
        }

        $documentoReferencia = $_POST['documentoReferencia'] ?? null;
        $motivo = $_POST['motivo'];
        $productos = $_POST['productos'] ?? [];
        
        $usuarioCreacion = $_SESSION['usuario'] ?? 'sistema';
        $idUsuario = $_SESSION['idUsuario'] ?? 1; 

        $productos_listos = [];
        foreach ($productos as $producto) {
            $productos_listos[] = [
                'id' => $producto['id'],
                'cantidad' => (int)$producto['cantidad'],
                'observacion' => $producto['observacion'] ?? '' 
            ];
        }

        try {
            if (empty($productos_listos)) {
                throw new Exception("Debe especificar al menos un producto a devolver.");
            }

            $idDevolucion = $this->devolucionModel->registrarDevolucion(
                $documentoReferencia, 
                $motivo, 
                $idUsuario, 
                $productos_listos, 
                $usuarioCreacion
            );

            $this->stockModel->procesarEntradaStock(
                self::ID_TIPO_DEVOLUCION,
                $idUsuario,
                $idDevolucion,
                $productos_listos,
                $usuarioCreacion
            );

            header('Location: index.php?c=devolucion&a=listar&msg=success_devolucion&id=' . $idDevolucion);
            
        } catch (Exception $e) {
            error_log("Error al procesar la devolución: " . $e->getMessage());
            header('Location: index.php?c=devolucion&a=form&msg=error_devolucion');
        }
    }
    
    public function listar() {
            require_once __DIR__ . '/../view/devolucion/listar.php'; 
    }
}