<?php
require_once __DIR__ . '/../model/Usuario.php';

class UsuarioController {
    private $model;

    public function __construct() {
        $this->model = new Usuario();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // === BLOQUE DE SEGURIDAD POR ROL (CORREGIDO) ===
        // 1. Obtenemos el rol. Si no existe, es una cadena vacía.
        $rolUsuario = $_SESSION['nombreRol'] ?? '';
        
        // 2. Verificamos la seguridad: Bloqueamos si el usuario no está logueado 
        // (no existe $_SESSION['usuario']) O si el rol NO es Administrador.
        if (!isset($_SESSION['usuario']) || $rolUsuario !== 'Administrador') {
            header("Location: index.php?c=dashboard&a=index");
            exit();
        }
        // ===============================================
    }

    public function listar() {
        $terminoBusqueda = $_GET['q'] ?? '';
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $usuariosPorPagina = 10;
        $offset = ($paginaActual - 1) * $usuariosPorPagina;

        $usuarios = $this->model->listar($terminoBusqueda, $usuariosPorPagina, $offset);
        $totalUsuarios = $this->model->contarUsuarios($terminoBusqueda);
        $totalPaginas = ceil($totalUsuarios / $usuariosPorPagina);

        require_once __DIR__ . '/../view/usuario/listar.php';
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->eliminar($id);
        }
        header("Location: index.php?c=usuario&a=listar");
        exit;
    }
}