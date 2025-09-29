<?php
require_once __DIR__ . '/../model/Usuario.php';

class LoginController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function login($usuario, $clave) {
    $user = $this->usuarioModel->validarLogin($usuario, $clave);
    
    if ($user) {
        session_start();
        $_SESSION['usuario']    = $user['usuario'];
        $_SESSION['correo']     = $user['correo'];
        $_SESSION['idRol']      = $user['idRol'];
        $_SESSION['nombreRol']  = $user['nombreRol'];
        
        header("Location: /TPWEB/ProyectoInventario/index.php?c=dashboard&a=index");
        exit;
    } else {
        header("Location: /TPWEB/ProyectoInventario/view/Login.php?error=1");
        exit;
    }
}


    public function dashboard() {

        if(isset($_SESSION['usuario'])) {
            require_once __DIR__ . '/../view/Dashboard.php';
        } else {
            header("Location: /TPWEB/ProyectoInventario/view/Login.php");
            exit;
        }
    }
}