<?php
require_once __DIR__ . '/../config/Conexion.php';
require_once 'Stock.php';
require_once 'Pedido.php';

class Recepcion {
    private $conn;
    private $stockModel;
    private $pedidoModel;
    
    private const ID_ESTADO_RECIBIDO = 6; 
    private const ID_TIPO_COMPRA = 1; 
    
    public function __construct() {
        $this->conn = (new Database())->getConnection();
        $this->stockModel = new Stock();
        $this->pedidoModel = new Pedido();
    }

    public function procesarRecepcion(
        $idPedido, 
        $idUsuario,
        $productosRecibidos,
        $usuarioCreacion
    ) {
        $this->conn->beginTransaction();
        
        try {
            $this->actualizarDetallesRecepcion(
                $idPedido, 
                $productosRecibidos, 
                $usuarioCreacion
            );
            
            $detallesParaStock = $this->obtenerDetallesParaStock($idPedido);
            
            $this->stockModel->procesarEntradaStock(
                self::ID_TIPO_COMPRA,
                $idUsuario, 
                $idPedido, 
                $detallesParaStock,
                $usuarioCreacion
            );

            $this->pedidoModel->actualizarEstado(
                $idPedido, 
                self::ID_ESTADO_RECIBIDO, 
                $usuarioCreacion
            );
            
            $this->conn->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception("Fallo en la transacción de Recepción de Pedido: " . $e->getMessage());
        }
    }

    private function actualizarDetallesRecepcion(
        $idPedido, 
        $productos, 
        $usuarioModificacion
    ) {
        foreach ($productos as $item) {
            $stmt = $this->conn->prepare("CALL SP_ActualizarDetalleRecepcion(?, ?, ?, ?, ?)");
            $stmt->execute([
                $item['idDetalle'],
                $item['cantidad'],
                $item['costoUnitario'],
                $item['fechaVencimiento'],
                $usuarioModificacion
            ]);
            $stmt->closeCursor();
        }
    }

    private function obtenerDetallesParaStock($idPedido) {
        $stmt = $this->conn->prepare("CALL SP_ObtenerDetallesRecepcion(?)");
        $stmt->execute([$idPedido]); 
        
        $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($detalles)) {
            throw new Exception("Error: No se encontraron detalles válidos para procesar el stock.");
        }
        
        $productosParaStock = [];
        foreach ($detalles as $detalle) {
            $productosParaStock[] = [
                'id' => $detalle['idProducto'],
                'cantidad' => $detalle['cantidad'],
                'costo' => $detalle['costo'],
                'fechaVencimiento' => $detalle['fechaVencimiento'],
            ];
        }
        return $productosParaStock;
    }
}