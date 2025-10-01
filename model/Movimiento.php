<?php
require_once __DIR__ . '/../config/Conexion.php';

class Movimiento {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function getConnection() {
        return $this->conn;
    }

    public function registrarEncabezado(
        $idTipoMovimiento, 
        $idUsuario, 
        $documentoReferenciaId, 
        $usuarioCreacion
    ) {
        try {
            $stmt = $this->conn->prepare("CALL SP_RegistrarMovimiento(?, ?, ?, ?, @idMovimiento)");
            $stmt->execute([
                $idTipoMovimiento, 
                $idUsuario, 
                $documentoReferenciaId, 
                $usuarioCreacion
            ]);
            $stmt->closeCursor();

            $idMovimiento = $this->conn->query("SELECT @idMovimiento")->fetchColumn();

            if (!$idMovimiento) {
                throw new Exception("El procedimiento almacenado no devolvió un ID válido.");
            }
            
            return $idMovimiento;
            
        } catch (PDOException $e) {
            throw new Exception("Error al registrar el encabezado de Movimiento (Ref: " . $documentoReferenciaId . "): " . $e->getMessage());
        }
    }
}