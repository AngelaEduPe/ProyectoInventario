<?php
class Devolucion {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function registrarDevolucion(
        $documentoReferencia, 
        $motivo, 
        $idUsuario, 
        $productos, 
        $usuarioCreacion
    ) {
        $this->conn->beginTransaction();
        
        try {
            $stmtEncabezado = $this->conn->prepare("CALL SP_RegistrarEncabezadoDevolucion(?, ?, ?, ?, @idDevolucion)");
            $stmtEncabezado->execute([
                $documentoReferencia,
                $motivo,
                $idUsuario,
                $usuarioCreacion
            ]);
            $stmtEncabezado->closeCursor();

            $idDevolucion = $this->conn->query("SELECT @idDevolucion")->fetchColumn();
            if (!$idDevolucion) {
                throw new Exception("Error al obtener idDevolucion.");
            }

            foreach ($productos as $item) {
                $stmtDetalle = $this->conn->prepare("CALL SP_RegistrarDetalleDevolucion(?, ?, ?, ?, ?)");
                $stmtDetalle->execute([
                    $idDevolucion,
                    $item['id'],
                    $item['cantidad'],
                    $item['observacion'],
                    $usuarioCreacion
                ]);
                $stmtDetalle->closeCursor();
            }

            $this->conn->commit();
            return $idDevolucion;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception("Fallo al registrar la Devolución: " . $e->getMessage());
        }
    }
}