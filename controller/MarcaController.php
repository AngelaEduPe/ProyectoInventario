<?php
require_once __DIR__ . '/../model/Marca.php';

class MarcaController {
    private $model;

    public function __construct() {
        $this->model = new Marca();
    }

    public function listar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $terminoBusqueda = $_GET['q'] ?? '';
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $marcasPorPagina = 10;
        $offset = ($paginaActual - 1) * $marcasPorPagina;

        $marcas = $this->model->listar($terminoBusqueda, $marcasPorPagina, $offset);
        $totalMarcas = $this->model->contarMarcas($terminoBusqueda);
        $totalPaginas = ceil($totalMarcas / $marcasPorPagina);

        require_once __DIR__ . '/../view/marca/listar.php';
    }

    public function crear() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . '/../view/marca/form.php';
    }

    public function guardar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $marca = new Marca();
        $marca->nombre = $_POST['nombre'];
        $marca->usuarioCreacion = $_SESSION['usuario'];

        $this->model->registrar($marca);
        header("Location: index.php?c=marca&a=listar");
        exit;
    }

    public function editar($id) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $marca = $this->model->obtener($id);
        require_once __DIR__ . '/../view/marca/form.php';
    }

    public function actualizar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $marca = new Marca();
        $marca->idMarca = $_POST['idMarca'];
        $marca->nombre = $_POST['nombre'];
        $marca->usuarioCreacion = $_SESSION['usuario'];

        $this->model->actualizar($marca);
        header("Location: index.php?c=marca&a=listar");
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
        header("Location: index.php?c=marca&a=listar");
        exit;
    }
}
