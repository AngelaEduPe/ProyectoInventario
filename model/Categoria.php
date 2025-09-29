<?php
require_once __DIR__ . '/../config/Conexion.php';

class Categoria {
    private $pdo;

    public $idCategoria;
    public $codigo;
    public $nombre;
    public $usuarioCreacion;
    public $usuarioModificacion;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function listar($terminoBusqueda = '', $limit = 10, $offset = 0) {
        try {
            $sql = "CALL sp_listar_categorias_paginado(?, ?, ?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$terminoBusqueda, $limit, $offset]);
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function contar($terminoBusqueda = '') {
        try {
            $sql = "CALL sp_contar_categorias(?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$terminoBusqueda]);
            return $stm->fetchColumn();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function obtener($id) {
        try {
            $sql = "CALL sp_obtener_categoria_por_id(?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function registrar($data) {
        try {
            $sql = "CALL sp_registrar_categoria(?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->codigo,
                $data->nombre,
                $data->usuarioCreacion
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function actualizar($data) {
        try {
            $sql = "CALL sp_actualizar_categoria(?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->idCategoria,
                $data->codigo,
                $data->nombre,
                $data->usuarioModificacion
            ]);
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

            $sql = "CALL sp_eliminar_categoria(?, ?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id, $usuarioModificacion]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}