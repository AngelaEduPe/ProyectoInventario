<?php
require_once __DIR__ . '/../config/Conexion.php';
require_once __DIR__ . '/Movimiento.php';

class Stock {
    private $conn;
    private $movimientoModel;

    private const ID_TIPO_COMPRA = 1;
    private const ID_TIPO_DEVOLUCION = 2; 
    private const ID_TIPO_TRASLADO = 3;
    private const ID_TIPO_DESCARTE = 4;
    private const ID_TIPO_AJUSTE_ENTRADA = 5; 
    
    public function __construct() {
        $this->conn = (new Database())->getConnection();
        $this->movimientoModel = new Movimiento();
    }
    
    public function getConnection() {
        return $this->conn;
    }

    public function procesarEntradaStock(
        $idTipoMovimiento, 
        $idUsuario, 
        $idDocumentoReferencia, 
        $productos, 
        $usuarioCreacion
    ) {
        if (empty($productos)) {
            throw new Exception("La lista de productos no puede estar vacía.");
        }
        
        $this->conn->beginTransaction();
        
        try {
            $idMovimiento = $this->movimientoModel->registrarEncabezado(
                $idTipoMovimiento, 
                $idUsuario, 
                $idDocumentoReferencia, 
                $usuarioCreacion
            );

            foreach ($productos as $item) {
                $stmt = $this->conn->prepare("CALL SP_EntradaStock(?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $idMovimiento,
                    $item['id'],
                    $item['cantidad'], 
                    $item['costo'], 
                    $item['fechaVencimiento'],
                    $usuarioCreacion
                ]);
                $stmt->closeCursor();
                do {} while ($stmt->nextRowset());
            }

            $this->conn->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception("Fallo en la transacción de Entrada de Stock: " . $e->getMessage());
        }
    }

    public function procesarSalidaStock(
        $idTipoMovimiento,
        $idUsuario,
        $idDocumentoReferencia, 
        $productos, 
        $usuarioCreacion
    ) {
        if (empty($productos)) {
            throw new Exception("La lista de productos no puede estar vacía.");
        }

        $this->conn->beginTransaction();
        
        try {
            $idMovimientoGenerado = null;
            
            foreach ($productos as $item) {
                $stmt = $this->conn->prepare("CALL SP_SalidaStock_PrimerVencimiento(?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $idTipoMovimiento,
                    $idUsuario,
                    $idDocumentoReferencia, 
                    $item['id'],
                    $item['cantidad'],
                    $usuarioCreacion
                ]);
                
                $idMovimientoGenerado = $stmt->fetchColumn(); 
                
                $stmt->closeCursor(); 
                do {} while ($stmt->nextRowset());
            }

            $this->conn->commit();
            return $idMovimientoGenerado ?? true;
            
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception("Fallo en la transacción de Salida de Stock (FIFO): " . $e->getMessage());
        }
    }

    public function buscarProductosConStock($termino) {
        try {
            $sql = "CALL SP_BuscarProductosConStock(?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $termino); 
            $stmt->execute();
            
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            do {} while ($stmt->nextRowset());
            return $data;
        } catch (PDOException $e) {
            throw new Exception("Error al buscar productos con stock usando SP: " . $e->getMessage());
        }
    }
    
    public function obtenerProductosBajoStock() {
        try {
            $sql = "CALL sp_obtener_productos_bajo_stock()";
            $stm = $this->conn->prepare($sql);
            $stm->execute();
            $data = $stm->fetchAll(PDO::FETCH_ASSOC);
            $stm->closeCursor();
            do {} while ($stm->nextRowset());
            return $data;
        } catch (Exception $e) {
            throw new Exception("Error al obtener productos con bajo stock: " . $e->getMessage());
        }
    }

    public function obtenerMovimientos() {
        try {
            $sql = "CALL SP_ListarMovimientos()";
            $stm = $this->conn->prepare($sql);
            $stm->execute();
            $movimientos = $stm->fetchAll(PDO::FETCH_ASSOC);
            $stm->closeCursor();
            do {} while ($stm->nextRowset());
            return $movimientos;
        } catch (Exception $e) {
            error_log("Error al obtener movimientos: " . $e->getMessage());
            return [];
        }
    }
}