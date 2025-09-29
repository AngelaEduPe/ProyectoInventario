<?php
require_once __DIR__ . '/../config/Conexion.php';

class Usuario {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function validarLogin($usuario, $contrasena) {
        $sql = "CALL sp_validarLogin(:usuario, :contrasena)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":contrasena", $contrasena);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listar($terminoBusqueda = '', $limit = 10, $offset = 0) {
        try {
            $sql = "CALL sp_listarUsuarios(:termino, :limit, :offset)";
            $stmt = $this->conn->prepare($sql);
            $like = $terminoBusqueda;
            $stmt->bindParam(':termino', $like, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function contarUsuarios($terminoBusqueda = '') {
        try {
            $sql = "CALL sp_contarUsuarios(:termino)";
            $stmt = $this->conn->prepare($sql);
            $like = $terminoBusqueda;
            $stmt->bindParam(':termino', $like, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function eliminar($id) {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $usuarioModificacion = $_SESSION['usuario'] ?? 'Sistema';

            $sql = "CALL sp_eliminarUsuario(?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->bindParam(2, $usuarioModificacion, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}