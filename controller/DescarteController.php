<?php
require_once __DIR__ . '/../model/Descarte.php';
require_once __DIR__ . '/../model/Stock.php';
require_once __DIR__ . '/../config/Conexion.php';

class DescarteController {
    private $descarteModel;
    private $stockModel;

    private const ID_TIPO_DESCARTE = 4; 

    public function __construct() {
        $this->descarteModel = new Descarte();
        $this->stockModel = new Stock();
    }

    public function form() {
        require_once __DIR__ . '/../view/descarte/form.php';
    }

    public function registrarNuevoDescarte() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=descarte&a=form');
            return;
        }
        
        $razon = $_POST['razon'] ?? '';
        $fechaEvento = $_POST['fechaEvento'] ?? date('Y-m-d');
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
                throw new Exception("Debe especificar al menos un producto para descartar.");
            }

            $idDescarte = $this->descarteModel->registrarDescarte(
                $razon, 
                $idUsuario, 
                $fechaEvento, 
                $productos_listos,
                $usuarioCreacion
            );

            $this->stockModel->procesarSalidaStock(
                self::ID_TIPO_DESCARTE,
                $idUsuario,
                $idDescarte,
                $productos_listos,
                $usuarioCreacion
            );

            header('Location: index.php?c=descarte&a=listar&msg=success_descarte&id=' . $idDescarte);

        } catch (Exception $e) {
            error_log("Error al procesar el descarte: " . $e->getMessage());
            header('Location: index.php?c=descarte&a=form&msg=error_descarte');
        }
    }

    public function listar() {
        require_once __DIR__ . '/../view/descarte/listar.php';
    }
}