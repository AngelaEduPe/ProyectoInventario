<?php
require_once __DIR__ . '/../config/Conexion.php';

class Proveedor {
    private $pdo;

    public $idProveedor;
    public $numeroDocumento;
    public $razonSocial;
    public $nombreComercial;
    public $contacto;
    public $telefono;
    public $direccion;
    public $usuarioCreacion;
    public $usuarioModificacion;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function listar($terminoBusqueda = '', $limit = 10, $offset = 0) {
        try {
            $sql = "CALL sp_listar_proveedores_paginado(?, ?, ?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$terminoBusqueda, $limit, $offset]);
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function contarProveedores($terminoBusqueda = '') {
        try {
            $sql = "CALL sp_contar_proveedores(?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$terminoBusqueda]);
            return $stm->fetchColumn();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function obtener($id) {
        try {
            $sql = "CALL sp_obtener_proveedor_por_id(?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function registrar($data) {
        try {
            $sql = "CALL sp_registrar_proveedor(?, ?, ?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->numeroDocumento,
                $data->razonSocial,
                $data->nombreComercial,
                $data->contacto,
                $data->telefono,
                $data->direccion,
                $data->usuarioCreacion
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function actualizar($data) {
        try {
            $sql = "CALL sp_actualizar_proveedor(?, ?, ?, ?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->idProveedor,
                $data->numeroDocumento,
                $data->razonSocial,
                $data->nombreComercial,
                $data->contacto,
                $data->telefono,
                $data->direccion,
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

            $sql = "CALL sp_eliminar_proveedor(?, ?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id, $usuarioModificacion]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}