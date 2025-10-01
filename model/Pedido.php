<?php

require_once __DIR__ . '/../config/Conexion.php';

class Pedido {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }


    public function crearPedido(
        $idProveedor, 
        $idUsuario, 
        $productos, 
        $usuarioCreacion
    ) {
        $this->conn->beginTransaction();
        
        try {
            $stmtEncabezado = $this->conn->prepare("CALL SP_CrearEncabezadoPedido(?, ?, ?, @idPedido)");
            $stmtEncabezado->execute([
                $idProveedor,
                $idUsuario,
                $usuarioCreacion
            ]);
            $stmtEncabezado->closeCursor();

            $idPedido = $this->conn->query("SELECT @idPedido")->fetchColumn();
            if (!$idPedido) {
                throw new Exception("Error al obtener idPedido.");
            }

            foreach ($productos as $item) {
                $costoUnitario = $item['costo'] ?? 0;
                $fechaVencimiento = $item['fechaVencimiento'] ?: null;
                
                $stmtDetalle = $this->conn->prepare("CALL SP_CrearDetallePedido(?, ?, ?, ?, ?, ?)");
                $stmtDetalle->execute([
                    $idPedido,
                    $item['id'],
                    $item['cantidad'],
                    $costoUnitario,
                    $fechaVencimiento,
                    $usuarioCreacion
                ]);
                $stmtDetalle->closeCursor();
            }

            $this->conn->commit();
            return $idPedido;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception("Fallo al crear el Pedido: " . $e->getMessage());
        }
    }

    public function obtenerTodosLosPedidos() {
        try {
            $sql = "CALL SP_ListarTodosPedidos()";
            $stm = $this->conn->prepare($sql);
            $stm->execute();
            $pedidos = $stm->fetchAll(PDO::FETCH_ASSOC);
            $stm->closeCursor();

            do {} while ($stm->nextRowset());
            
            return $pedidos;

        } catch (Exception $e) {
            error_log("Error al listar pedidos: " . $e->getMessage());
            return [];
        }
    }
    

    public function obtenerDetallePedidoParaRecepcion($idPedido) {
        try {
            $sql = "CALL SP_ObtenerDetallePedidoParaRecepcion(?)";
            $stm = $this->conn->prepare($sql);
            $stm->execute([$idPedido]);
            $detalles = $stm->fetchAll(PDO::FETCH_ASSOC);
            $stm->closeCursor();

            do {} while ($stm->nextRowset());
            
            return $detalles;
        } catch (Exception $e) {
            error_log("Error al obtener detalles de pedido para recepción: " . $e->getMessage());
            return [];
        }
    }

    
}