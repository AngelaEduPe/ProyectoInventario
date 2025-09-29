<?php
require_once __DIR__ . '/../model/Categoria.php';

class CategoriaController {
    private $model;

    public function __construct() {
        $this->model = new Categoria();
    }

    public function listar() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $termino = $_GET['q'] ?? '';
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 10;
        $offset = ($paginaActual - 1) * $porPagina;

        $categorias = $this->model->listar($termino, $porPagina, $offset);
        $total = $this->model->contar($termino);
        $totalPaginas = ceil($total / $porPagina);

        require_once __DIR__ . '/../view/categoria/listar.php';
    }

    public function crear() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/../view/categoria/form.php';
    }

    public function guardar() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $cat = new Categoria();
        $cat->codigo = $_POST['codigo'];
        $cat->nombre = $_POST['nombre'];
        $cat->usuarioCreacion = $_SESSION['usuario'];

        $this->model->registrar($cat);
        header("Location: index.php?c=categoria&a=listar");
        exit;
    }

    public function editar($id) {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $categoria = $this->model->obtener($id);
        require_once __DIR__ . '/../view/categoria/form.php';
    }

    public function actualizar() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $cat = new Categoria();
        $cat->idCategoria = $_POST['idCategoria'];
        $cat->codigo = $_POST['codigo'];
        $cat->nombre = $_POST['nombre'];
        $cat->usuarioModificacion = $_SESSION['usuario'];

        $this->model->actualizar($cat);
        header("Location: index.php?c=categoria&a=listar");
        exit;
    }

    public function eliminar() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $id = $_GET['id'] ?? null;
        if ($id) $this->model->eliminar($id);

        header("Location: index.php?c=categoria&a=listar");
        exit;
    }
}
