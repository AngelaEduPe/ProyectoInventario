<?php
require_once __DIR__ . '/../model/Proveedor.php';

class ProveedorController {
    private $model;

    public function __construct() {
        $this->model = new Proveedor();
    }

    public function listar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $terminoBusqueda = $_GET['q'] ?? '';
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $proveedoresPorPagina = 10;
        $offset = ($paginaActual - 1) * $proveedoresPorPagina;

        $proveedores = $this->model->listar($terminoBusqueda, $proveedoresPorPagina, $offset);
        $totalProveedores = $this->model->contarProveedores($terminoBusqueda);
        $totalPaginas = ceil($totalProveedores / $proveedoresPorPagina);

        require_once __DIR__ . '/../view/proveedor/listar.php';
    }

    public function crear() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        require_once __DIR__ . '/../view/proveedor/form.php';
    }

    public function guardar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $proveedor = new Proveedor();
        $proveedor->numeroDocumento = $_POST['numeroDocumento'];
        $proveedor->razonSocial = $_POST['razonSocial'];
        $proveedor->nombreComercial = $_POST['nombreComercial'] ?? null;
        $proveedor->contacto = $_POST['contacto'] ?? null;
        $proveedor->telefono = $_POST['telefono'] ?? null;
        $proveedor->direccion = $_POST['direccion'] ?? null;
        $proveedor->usuarioCreacion = $_SESSION['usuario'];

        $this->model->registrar($proveedor);
        header("Location: index.php?c=proveedor&a=listar");
        exit;
    }

    public function editar($id) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $proveedor = $this->model->obtener($id);
        require_once __DIR__ . '/../view/proveedor/form.php';
    }

    public function actualizar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $proveedor = new Proveedor();
        $proveedor->idProveedor = $_POST['idProveedor'];
        $proveedor->numeroDocumento = $_POST['numeroDocumento'];
        $proveedor->razonSocial = $_POST['razonSocial'];
        $proveedor->nombreComercial = $_POST['nombreComercial'] ?? null;
        $proveedor->contacto = $_POST['contacto'] ?? null;
        $proveedor->telefono = $_POST['telefono'] ?? null;
        $proveedor->direccion = $_POST['direccion'] ?? null;
        $proveedor->usuarioModificacion = $_SESSION['usuario'];

        $this->model->actualizar($proveedor);
        header("Location: index.php?c=proveedor&a=listar");
        exit;
    }

    public function eliminar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->eliminar($id);
        }
        header("Location: index.php?c=proveedor&a=listar");
        exit;
    }
}
