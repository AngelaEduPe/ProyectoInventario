<?php
require_once __DIR__ . '/../model/Recepcion.php';
require_once __DIR__ . '/../model/Stock.php'; 
require_once __DIR__ . '/../model/Pedido.php';

class RecepcionController {
    private $recepcionModel;
    private $pedidoModel;

    public function __construct() {
        $this->recepcionModel = new Recepcion();
        $this->pedidoModel = new Pedido(); 
    }

    public function form() {
        require_once __DIR__ . '/../view/recepcion/form.php';
    }

    public function procesarRecepcion($datos = null) {
        
        header('Content-Type: application/json');

        if ($datos === null) {
            $input = file_get_contents('php://input');
            $datos = json_decode($input, true);
        }
        
        $idPedido = $datos['idPedido'] ?? null;
        $productosRecibidos = $datos['productos'] ?? [];
        
        $usuarioCreacion = $_SESSION['usuario'] ?? 'Sistema';
        $idUsuario = $_SESSION['idUsuario'] ?? 1; 

        if (!$idPedido || empty($productosRecibidos)) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos esenciales (Pedido o productos).']);
            return;
        }

        try { 
            $this->recepcionModel->procesarRecepcion(
                $idPedido, 
                $idUsuario,
                $productosRecibidos,
                $usuarioCreacion
            );

            echo json_encode([
                'success' => true, 
                'idPedido' => $idPedido, 
                'message' => 'Recepción completada. Stock ingresado y pedido finalizado.'
            ]);

        } catch (Exception $e) {
            error_log("Error en RecepcionController (Pedido #{$idPedido}): " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'Fallo al procesar la recepción.'
            ]);
        }
    }

    public function buscarPedido() {
        $idPedido = $_GET['id'] ?? null;
        header('Content-Type: application/json');

        if (!$idPedido) {
            echo json_encode(['success' => false, 'message' => 'ID de Pedido no especificado.']);
            return;
        }

        try {
            require_once __DIR__ . '/../model/Pedido.php';
            $pedidoModel = new Pedido();
            $detalles = $pedidoModel->obtenerDetallesParaRecepcion($idPedido); 

            if (empty($detalles)) {
                echo json_encode(['success' => false, 'message' => "Pedido #{$idPedido} no encontrado o no está en estado PENDIENTE."]);
                return;
            }

            echo json_encode(['success' => true, 'data' => $detalles]);
        } catch (\Exception $e) {
            error_log("Error al buscar pedido: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor al buscar pedido.']);
        }
    }
}