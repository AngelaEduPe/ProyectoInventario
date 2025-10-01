<?php
require_once __DIR__ . '/../config/Conexion.php';
class Traslado {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function registrarTraslado(
        $destino, 
        $idUsuario, 
        $productos, 
        $usuarioCreacion
    ) {
        $this->conn->beginTransaction();
        
        try {
            $stmtEncabezado = $this->conn->prepare("CALL SP_RegistrarEncabezadoTraslado(?, ?, ?, @idTraslado)");
            $stmtEncabezado->execute([
                $destino,
                $idUsuario,
                $usuarioCreacion
            ]);
            $stmtEncabezado->closeCursor();

            $idTraslado = $this->conn->query("SELECT @idTraslado")->fetchColumn();
            if (!$idTraslado) {
                throw new Exception("Error al obtener idTraslado.");
            }
            
            foreach ($productos as $item) {
                $stmtDetalle = $this->conn->prepare("CALL SP_RegistrarDetalleTraslado(?, ?, ?, ?, ?)");
                $stmtDetalle->execute([
                    $idTraslado,
                    $item['id'],
                    $item['cantidad'],
                    $item['observacion'],
                    $usuarioCreacion
                ]);
                $stmtDetalle->closeCursor();
            }

            $this->conn->commit();
            return $idTraslado;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception("Fallo al registrar el Traslado: " . $e->getMessage());
        }
    }
}