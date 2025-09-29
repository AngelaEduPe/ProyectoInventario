<?php
require_once __DIR__ . '/../config/Conexion.php';
require_once __DIR__ . '/../model/Subcategoria.php';
require_once __DIR__ . '/../model/Marca.php';

class Producto {
    private $pdo;

    public $idProducto;
    public $sku;
    public $codigoBarras;
    public $nombre;
    public $descripcion;
    public $idSubcategoria;
    public $idMarca;
    public $stockMinimo;
    public $precioCosto;
    public $usuarioCreacion;
    public $usuarioModificacion;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function listar($terminoBusqueda = '', $limit = 10, $offset = 0) {
        try {
            $sql = "CALL sp_listar_productos(?, ?, ?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$terminoBusqueda, $limit, $offset]);
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function contarProductos($terminoBusqueda = '') {
        try {
            $sql = "CALL sp_contar_productos(?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$terminoBusqueda]);
            return $stm->fetchColumn();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function obtener($id) {
        try {
            $sql = "CALL sp_obtener_producto(?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function registrar($data) {
        try {
            $sql = "CALL sp_registrar_producto(?, ?, ?, ?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->codigoBarras,
                $data->nombre,
                $data->descripcion,
                $data->idSubcategoria,
                $data->idMarca,
                $data->stockMinimo,
                $data->precioCosto,
                $data->usuarioCreacion
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function actualizar($data) {
        try {
            $sql = "CALL sp_actualizar_producto(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->idProducto,
                $data->codigoBarras,
                $data->nombre,
                $data->descripcion,
                $data->idSubcategoria,
                $data->idMarca,
                $data->stockMinimo,
                $data->precioCosto,
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

            $sql = "CALL sp_eliminar_producto(?, ?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id, $usuarioModificacion]);
            } catch (Exception $e) {
            die($e->getMessage());
            }
            }


    public function obtenerReporteStock() {
        try {
            $sql = "CALL sp_obtener_reporte_stock()";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function buscarProductos($termino) {
        try {
            $sql = "SELECT idProducto, nombre, sku, codigoBarras FROM Producto WHERE nombre LIKE ? OR sku LIKE ? AND esDesactivado = 0 ORDER BY nombre ASC LIMIT 10";
            $stm = $this->pdo->prepare($sql);
            $searchTerm = '%' . $termino . '%';
            $stm->execute([$searchTerm, $searchTerm]);
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function insertarStock($idProducto, $cantidad, $fechaVencimiento, $usuario) {
        try {
            $sql = "CALL sp_gestionar_stock(?, ?, ?, ?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([
                $idProducto,
                $cantidad,
                $fechaVencimiento,
                $usuario
            ]);
            return true;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


    public function obtenerProductosBajoStock() {
        try {
            $sql = "CALL sp_obtener_productos_bajo_stock()";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Error al obtener productos con bajo stock: " . $e->getMessage());
        }
    }


}
