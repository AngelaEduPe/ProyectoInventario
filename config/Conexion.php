<?php
class Database {
    private $host = "localhost";
    private $dbname = "SistemaInventario";
    private $user = "root";
    private $pass = "123456";

    public function getConnection() {
        try {
            $conn = new PDO("mysql:host=$this->host;dbname=$this->dbname",
                $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
