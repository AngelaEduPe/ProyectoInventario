<?php
session_start();

require_once __DIR__ . '/controller/LoginController.php';
require_once __DIR__ . '/controller/ProductoController.php';
require_once __DIR__ . '/controller/DashboardController.php';
require_once __DIR__ . '/controller/ProveedorController.php';
require_once __DIR__ . '/model/Subcategoria.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: view/Login.php");
    exit;
}

if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    $controller = new LoginController();
    $controller->login($usuario, $clave);
    exit; 
}

if (isset($_GET['c'])) {
    $controllerName = ucfirst($_GET['c']) . 'Controller';
    $actionName = isset($_GET['a']) ? $_GET['a'] : 'index';

    $controllerFile = __DIR__ . '/controller/' . $controllerName . '.php';

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $controllerName();
        
        if (isset($_GET['id']) && method_exists($controller, $actionName)) {
            $controller->$actionName($_GET['id']);
        } else if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            echo "Error 404: Acción '$actionName' no encontrada.";
        }
    } else {
        echo "Error 404: Controlador '$controllerName' no encontrado.";
    }
} else {
    if (isset($_SESSION['usuario'])) {
        header("Location: index.php?c=dashboard&a=index");
        exit;
    } else {
        header("Location: view/Login.php");
        exit;
    }
}
