<?php
require_once __DIR__ . '/../model/Traslado.php';
require_once __DIR__ . '/../model/Stock.php';
require_once __DIR__ . '/../model/Tienda.php';
require_once __DIR__ . '/../config/Conexion.php';

class TrasladoController {
    private $trasladoModel;
    private $stockModel;
    private $tiendaModel;

    private const ID_TIPO_TRASLADO = 3; 

    public function __construct() {
        $this->trasladoModel = new Traslado();
        $this->stockModel = new Stock();
        $this->tiendaModel = new Tienda(); 
    }

    public function form() {
        try {
            $tiendas = $this->tiendaModel->listarTiendas(); 
        } catch (Exception $e) {
            error_log("Error al cargar tiendas para traslado: " . $e->getMessage());
            $tiendas = [];
        }
        require_once __DIR__ . '/../view/traslado/form.php';
    }

    public function listar() {
        require_once __DIR__ . '/../view/traslado/listar.php';
    }

    public function registrarNuevoTraslado() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=traslado&a=form');
            exit;
        }

        $destino = $_POST['destino'] ?? null;
        $observacionGeneral = $_POST['observacion'] ?? '';
        $productosPost = $_POST['productos'] ?? [];
        
        $usuarioCreacion = $_SESSION['usuario'] ?? 'Sistema';
        $idUsuario = $_SESSION['idUsuario'] ?? 1; 

        try {
            if (!$destino || empty($productosPost)) {
                throw new Exception("Debe especificar un destino y al menos un producto.");
            }

            $productos = [];
            foreach ($productosPost as $idProd => $item) {
                $productos[] = [
                    'id' => (int)$idProd,
                    'cantidad' => (int)$item['cantidad'],
                    'observacion' => $observacionGeneral 
                ];
            }
            
            error_log("DEBUG: Productos a trasladar (ID, Cantidad): " . print_r($productos, true));
            
            $idTraslado = $this->trasladoModel->registrarTraslado(
                $destino, 
                $idUsuario, 
                $productos, 
                $usuarioCreacion
            );

            $this->stockModel->procesarSalidaStock(
                self::ID_TIPO_TRASLADO,
                $idUsuario,
                $idTraslado, 
                $productos, 
                $usuarioCreacion
            );

            header('Location: index.php?c=traslado&a=listar&msg=success_traslado&id=' . $idTraslado);

        } catch (Exception $e) {
            error_log("Error al procesar el traslado: " . $e->getMessage());
            header('Location: index.php?c=traslado&a=form&msg=error_traslado');
        }
        exit();
    }
}