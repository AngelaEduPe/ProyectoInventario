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

    // =========================================================
    // LÓGICA DE INGRESO DE STOCK (ID 1, 2, 5)
    // =========================================================
    public function procesarEntradaStock(
        $idTipoMovimiento, 
        $idUsuario, 
        $idDocumentoReferencia, 
        $productos, 
        $usuarioCreacion
    ) {
        $this->conn->beginTransaction();
        
        try {
            $idMovimiento = $this->movimientoModel->registrarEncabezado(
                $idTipoMovimiento, 
                $idUsuario, 
                $idDocumentoReferencia, 
                $usuarioCreacion
            );

            foreach ($productos as $item) {
                $stmt = $this->conn->prepare("CALL SP_EntradaStock(?, ?, ?, ?, ?, ?, @idStock)");
                $stmt->execute([
                    $idMovimiento,
                    $item['id'],
                    $item['cantidad'], 
                    $item['costo'], 
                    $item['fechaVencimiento'],
                    $usuarioCreacion
                ]);
                $stmt->closeCursor();
            }

            $this->conn->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception("Fallo en la transacción de Entrada de Stock: " . $e->getMessage());
        }
    }

    // =========================================================
    // LÓGICA DE SALIDA DE STOCK (ID 3, 4)
    // =========================================================
    public function procesarSalidaStock(
        $idTipoMovimiento,
        $idUsuario,
        $idDocumentoReferencia, 
        $productos, 
        $usuarioCreacion
    ) {
        $this->conn->beginTransaction();
        
        try {
            $idMovimiento = $this->movimientoModel->registrarEncabezado(
                $idTipoMovimiento,
                $idUsuario,
                $idDocumentoReferencia, 
                $usuarioCreacion
            );

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
                
                $stmt->fetch(PDO::FETCH_ASSOC); 
                $stmt->closeCursor(); 
            }

            $this->conn->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception("Fallo en la transacción de Salida de Stock: " . $e->getMessage());
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
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error al obtener productos con bajo stock: " . $e->getMessage());
        }
    }
}