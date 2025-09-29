<?php
require_once __DIR__ . '/../model/Subcategoria.php';
require_once __DIR__ . '/../model/Categoria.php';

class SubcategoriaController {
    private $model;

    public function __construct() {
        $this->model = new Subcategoria();
    }

    public function listar() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $termino = $_GET['q'] ?? '';
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 10;
        $offset = ($paginaActual - 1) * $porPagina;

        $subcategorias = $this->model->listar($termino, $porPagina, $offset);
        $total = $this->model->contar($termino);
        $totalPaginas = ceil($total / $porPagina);

        require_once __DIR__ . '/../view/subcategoria/listar.php';
    }

    public function crear() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $categoria = new Categoria();
        $categorias = $categoria->listar('', 1000, 0);
        require_once __DIR__ . '/../view/subcategoria/form.php';
    }

    public function guardar() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $sub = new Subcategoria();
        $sub->codigo = $_POST['codigo'];
        $sub->nombre = $_POST['nombre'];
        $sub->idCategoria = $_POST['idCategoria'];
        $sub->usuarioCreacion = $_SESSION['usuario'];

        $this->model->registrar($sub);
        header("Location: index.php?c=subcategoria&a=listar");
        exit;
    }

    public function editar($id) {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $subcategoria = $this->model->obtener($id);
        $categoria = new Categoria();
        $categorias = $categoria->listar('', 1000, 0);
        require_once __DIR__ . '/../view/subcategoria/form.php';
    }

    public function actualizar() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $sub = new Subcategoria();
        $sub->idSubcategoria = $_POST['idSubcategoria'];
        $sub->codigo = $_POST['codigo'];
        $sub->nombre = $_POST['nombre'];
        $sub->idCategoria = $_POST['idCategoria'];
        $sub->usuarioModificacion = $_SESSION['usuario'];

        $this->model->actualizar($sub);
        header("Location: index.php?c=subcategoria&a=listar");
        exit;
    }

    public function eliminar() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $id = $_GET['id'] ?? null;
        if ($id) $this->model->eliminar($id);

        header("Location: index.php?c=subcategoria&a=listar");
        exit;
    }
}
