<?php
require_once __DIR__ . '/../model/Producto.php';
require_once __DIR__ . '/../model/Subcategoria.php';
require_once __DIR__ . '/../model/Marca.php';

class ProductoController {
    private $model;

    public function __construct() {
        $this->model = new Producto();
    }

    public function listar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $terminoBusqueda = $_GET['q'] ?? '';
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $productosPorPagina = 10;
        $offset = ($paginaActual - 1) * $productosPorPagina;

        $productos = $this->model->listar($terminoBusqueda, $productosPorPagina, $offset);
        $totalProductos = $this->model->contarProductos($terminoBusqueda);
        $totalPaginas = ceil($totalProductos / $productosPorPagina);

        require_once __DIR__ . '/../view/producto/listar.php';
    }

    public function crear() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $subcategoria = new Subcategoria();
        $subcategorias = $subcategoria->listar();
        $marca = new Marca();
        $marcas = $marca->listar();
        require_once __DIR__ . '/../view/producto/form.php';
    }

    public function guardar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $producto = new Producto();
        $producto->codigoBarras  = $_POST['codigoBarras'];
        $producto->nombre        = $_POST['nombre'];
        $producto->descripcion   = $_POST['descripcion'];
        $producto->idSubcategoria= $_POST['idSubcategoria'];
        $producto->idMarca       = $_POST['idMarca'];
        $producto->stockMinimo   = $_POST['stockMinimo'];
        $producto->precioCosto   = $_POST['precioCosto'];
        $producto->usuarioCreacion = $_SESSION['usuario'];

        $this->model->registrar($producto);
        header("Location: index.php?c=producto&a=listar");
        exit;
    }

    public function editar($id) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $producto = $this->model->obtener($id);
        $subcategoria = new Subcategoria();
        $subcategorias = $subcategoria->listar();
        $marca = new Marca();
        $marcas = $marca->listar();
        require_once __DIR__ . '/../view/producto/form.php';
    }

    public function actualizar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $producto = new Producto();
        $producto->idProducto    = $_POST['idProducto'];
        $producto->codigoBarras  = $_POST['codigoBarras'];
        $producto->nombre        = $_POST['nombre'];
        $producto->descripcion   = $_POST['descripcion'];
        $producto->idSubcategoria= $_POST['idSubcategoria'];
        $producto->idMarca       = $_POST['idMarca'];
        $producto->stockMinimo   = $_POST['stockMinimo'];
        $producto->precioCosto   = $_POST['precioCosto'];
        $producto->usuarioModificacion = $_SESSION['usuario'];
        
        $this->model->actualizar($producto);
        header("Location: index.php?c=producto&a=listar");
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
        header("Location: index.php?c=producto&a=listar");
        exit;
    }
}
