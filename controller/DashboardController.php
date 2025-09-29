<?php
require_once __DIR__ . '/../config/Conexion.php';
require_once __DIR__ . '/../model/Producto.php'; 

class DashboardController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?c=login"); 
            exit();
        }

        $usuarioConectado = $_SESSION['usuario']; 
        $rolConectado = $_SESSION['nombreRol']; 
        $totalOnline = 1; 

        $stmt = $this->conn->query("CALL sp_total_productos()");
        $totalStock = $stmt->fetch(PDO::FETCH_ASSOC)['totalStock'] ?? 0;
        $stmt->closeCursor();

        $stmt = $this->conn->query("CALL sp_total_proveedores()");
        $totalProv = $stmt->fetch(PDO::FETCH_ASSOC)['totalProv'] ?? 0;
        $stmt->closeCursor();

        $stmt = $this->conn->query("CALL sp_total_usuarios()");
        $totalUser = $stmt->fetch(PDO::FETCH_ASSOC)['totalUser'] ?? 0;
        $stmt->closeCursor();
        
        $productoModel = new Producto();
        $productosBajoStock = $productoModel->obtenerProductosBajoStock();
        require_once __DIR__ . '/../view/Dashboard.php';
    }
}