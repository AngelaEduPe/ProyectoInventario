<?php
// model/Tienda.php
require_once __DIR__ . '/../config/Conexion.php';

class Tienda {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function listarTiendas() {
        try {
            $sql = "CALL SP_ListarTiendas()";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al listar tiendas activas: " . $e->getMessage());
        }
    }

    /**
     * Registra una nueva tienda.
     */
    public function insertarTienda($idDistrito, $nombre, $direccion, $telefono, $usuarioCreacion) {
        try {
            // 5 parámetros
            $sql = "CALL SP_InsertarTienda(?, ?, ?, ?, ?)"; 
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $idDistrito); 
            $stmt->bindParam(2, $nombre);
            $stmt->bindParam(3, $direccion);
            $stmt->bindParam(4, $telefono);
            $stmt->bindParam(5, $usuarioCreacion);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al insertar tienda: " . $e->getMessage());
        }
    }

    /**
     * Actualiza los datos de una tienda o la desactiva.
     */
    public function actualizarTienda($idTienda, $idDistrito, $nombre, $direccion, $telefono, $esDesactivado, $usuarioModificacion) {
        try {
            // 7 parámetros
            $sql = "CALL SP_ActualizarTienda(?, ?, ?, ?, ?, ?, ?)"; 
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $idTienda);
            $stmt->bindParam(2, $idDistrito); // ⬅️ idDistrito
            $stmt->bindParam(3, $nombre);
            $stmt->bindParam(4, $direccion);
            $stmt->bindParam(5, $telefono);
            $stmt->bindParam(6, $esDesactivado);
            $stmt->bindParam(7, $usuarioModificacion);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar tienda: " . $e->getMessage());
        }
    }
}