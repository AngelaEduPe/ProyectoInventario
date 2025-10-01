<?php
class Descarte {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function registrarDescarte(
        $motivo, 
        $idUsuario,
        $fechaEvento, 
        $productos, 
        $usuarioCreacion
    ) {
        $this->conn->beginTransaction();
        
        try {
            $stmtEncabezado = $this->conn->prepare("CALL SP_RegistrarEncabezadoDescarte(?, ?, ?, ?, @idDescarte)");
            $stmtEncabezado->execute([
                $motivo,
                $idUsuario,
                $fechaEvento,
                $usuarioCreacion
            ]);
            $stmtEncabezado->closeCursor();

            $idDescarte = $this->conn->query("SELECT @idDescarte")->fetchColumn();
            if (!$idDescarte) {
                throw new Exception("Error al obtener idDescarte.");
            }
            foreach ($productos as $item) {
                $stmtDetalle = $this->conn->prepare("CALL SP_RegistrarDetalleDescarte(?, ?, ?, ?, ?)");
                $stmtDetalle->execute([
                    $idDescarte,
                    $item['id'],
                    $item['cantidad'],
                    $item['observacion'],
                    $usuarioCreacion
                ]);
                $stmtDetalle->closeCursor();
            }

            $this->conn->commit();
            return $idDescarte;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception("Fallo al registrar el Descarte: " . $e->getMessage());
        }
    }




}