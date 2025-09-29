<?php
require_once __DIR__ . '/../config/Conexion.php';
require_once __DIR__ . '/Categoria.php';

class Subcategoria {
    private $pdo;

    public $idSubcategoria;
    public $codigo;
    public $nombre;
    public $idCategoria;
    public $usuarioCreacion;
    public $usuarioModificacion;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function listar($terminoBusqueda = '', $limit = 10, $offset = 0) {
        try {
            $sql = "CALL sp_listar_subcategorias_paginado(?, ?, ?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$terminoBusqueda, $limit, $offset]);
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function contar($terminoBusqueda = '') {
        try {
            $sql = "CALL sp_contar_subcategorias(?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$terminoBusqueda]);
            return $stm->fetchColumn();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function obtener($id) {
        try {
            $sql = "CALL sp_obtener_subcategoria_por_id(?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function registrar($data) {
        try {
            $sql = "CALL sp_registrar_subcategoria(?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->codigo,
                $data->nombre,
                $data->idCategoria,
                $data->usuarioCreacion
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function actualizar($data) {
        try {
            $sql = "CALL sp_actualizar_subcategoria(?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->idSubcategoria,
                $data->codigo,
                $data->nombre,
                $data->idCategoria,
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

            $sql = "CALL sp_eliminar_subcategoria(?, ?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id, $usuarioModificacion]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}