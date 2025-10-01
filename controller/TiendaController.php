<?php
require_once __DIR__ . '/../model/Tienda.php';
require_once __DIR__ . '/../model/Ubigeo.php'; 
require_once __DIR__ . '/../config/Conexion.php';

class TiendaController {
    private $tiendaModel;
    private $ubigeoModel;

    public function __construct() {
        $this->tiendaModel = new Tienda();
        $this->ubigeoModel = new Ubigeo();
    }

    public function form() {
        try {
            $departamentos = $this->ubigeoModel->obtenerDepartamentos(); 
            $tienda = null;
        } catch (Exception $e) {
            error_log("Error al cargar departamentos para formulario de Tienda: " . $e->getMessage());
            $departamentos = [];
        }
        
        require_once __DIR__ . '/../view/tienda/form.php';
    }
    
    public function listar() {
        try {
            $tiendas = $this->tiendaModel->listarTiendas(); 
        } catch (Exception $e) {
            error_log("Error al listar tiendas: " . $e->getMessage());
            $tiendas = [];
        }
        require_once __DIR__ . '/../view/tienda/listar.php';
    }

    public function insertar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=tienda&a=form');
            exit;
        }

        $idDistrito = $_POST['idDistrito'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $telefono = $_POST['telefono'] ?? null;
        
        $usuarioCreacion = $_SESSION['usuario'] ?? 'Sistema';

        try {
            if (!$idDistrito || empty($nombre) || empty($direccion)) {
                throw new Exception("Campos requeridos faltantes (Distrito, Nombre, Dirección).");
            }
            
            $this->tiendaModel->insertarTienda(
                $idDistrito, 
                $nombre, 
                $direccion, 
                $telefono, 
                $usuarioCreacion
            );

            header('Location: index.php?c=tienda&a=listar&msg=success_insert');
            
        } catch (Exception $e) {
            error_log("Error al registrar tienda: " . $e->getMessage());
            header('Location: index.php?c=tienda&a=form&msg=error_insert');
        }
        exit();
    }
    
}