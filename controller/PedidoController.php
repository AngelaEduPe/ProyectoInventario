<?php
require_once __DIR__ . '/../model/Pedido.php';
require_once __DIR__ . '/../model/Proveedor.php';
require_once __DIR__ . '/../config/Conexion.php';

class PedidoController {
    private $pedidoModel;
    private $proveedorModel;

    public function __construct() {
        $this->pedidoModel = new Pedido();
        $this->proveedorModel = new Proveedor();
    }

    public function form() {
        $proveedoresObj = $this->proveedorModel->listar(); 
        
        $proveedores = [];

        if (is_array($proveedoresObj)) {
            foreach ($proveedoresObj as $obj) {
                $proveedores[] = (array) $obj;
            }
        }

        require_once __DIR__ . '/../view/pedido/form.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=pedido&a=form');
            exit;
        }

        $idProveedor = $_POST['idProveedor'] ?? null;
        $productos = $_POST['productos'] ?? [];
        $usuarioCreacion = $_SESSION['usuario'] ?? 'Sistema';
        $idUsuario = $_SESSION['idUsuario'] ?? 1; 

        try {
            if (!$idProveedor || empty($productos)) {
                throw new Exception("Debe seleccionar un proveedor y añadir al menos un producto.");
            }

            $idPedido = $this->pedidoModel->crearPedido(
                $idProveedor, 
                $idUsuario, 
                $productos, 
                $usuarioCreacion
            );

            header('Location: index.php?c=pedido&a=listar&msg=success_registro_pedido&id=' . $idPedido);
            
        } catch (Exception $e) {
            error_log("Error al registrar pedido: " . $e->getMessage());
            header('Location: index.php?c=pedido&a=form&msg=error_registro');
        }
        exit();
    }

    public function listar() {
        $pedidos = $this->pedidoModel->obtenerTodosLosPedidos();
        require_once __DIR__ . '/../view/pedido/listar.php';
    }

    public function ver($id = null) {
        $id = $id ?? $_GET['id'] ?? null;

        if (!$id) {
            header('Location: index.php?c=pedido&a=listar&msg=error_not_found');
            exit;
        }
        
        $pedido = $this->pedidoModel->obtenerDetalles($id);
        if (!$pedido) {
            header('Location: index.php?c=pedido&a=listar&msg=error_not_found');
            exit;
        }
        require_once __DIR__ . '/../view/pedido/ver.php';
    }
}