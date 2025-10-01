<?php
require_once __DIR__ . '/../config/Conexion.php';

class Ubigeo {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }


    public function obtenerDepartamentos() {
        try {
            $sql = "CALL SP_ObtenerDepartamentos()";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $data;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener departamentos: " . $e->getMessage());
        }
    }

    public function obtenerProvinciasPorDepartamento($idDepartamento) {
        try {
            $sql = "CALL SP_ObtenerProvinciasPorDepartamento(?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $idDepartamento);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $data;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener provincias: " . $e->getMessage());
        }
    }

    public function obtenerDistritosPorProvincia($idProvincia) {
        try {
            $sql = "CALL SP_ObtenerDistritosPorProvincia(?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $idProvincia);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $data;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener distritos: " . $e->getMessage());
        }
    }
}