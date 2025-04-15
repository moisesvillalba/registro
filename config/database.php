<?php
// Configuración de la base de datos
class Database {
    // Parámetros de conexión
    private $host = "localhost";
    private $db_name = "sistema_registro";
    private $username = "root";
    private $password = "";
    public $conn;
    
    // Método para obtener la conexión
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>