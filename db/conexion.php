<?php
class Conexion {
    private $host = "127.0.0.1";
    private $db_name = "empleados";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConexion() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Establecer modo de error de PDO a excepción
        } catch (PDOException $exception) {
            // Loguear el error de conexión para fines de depuración (opcional)
            error_log("Error de conexión a la base de datos: " . $exception->getMessage());

            // Mostrar una alerta genérica
            echo "<script>alert('Inténtalo más tarde.');</script>";
            echo "<script>window.location.href = 'index.php';</script>"; // Redirigir después de la alerta
            exit(); // Detener ejecución
        }

        return $this->conn;
    }
}
?>
