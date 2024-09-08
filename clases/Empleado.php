<?php
require_once 'db/conexion.php';

class Empleado
{
    private $conn;

    public function __construct()
    {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    // Método para obtener la conexión
    public function getConexion()
    {
        return $this->conn;
    }

    // Método para crear un empleado
    public function crearEmpleado($nombre, $email, $sexo, $area_id, $boletin, $descripcion, $roles)
    {
        try {
            // Verificar si el correo electrónico ya existe
            if ($this->existeCorreo($email)) {
                echo "<script>alert('El correo electrónico ya está registrado. Inténtelo con otro.');</script>";
                return false;
            }

            $query = "INSERT INTO empleados (nombre, email, sexo, area_id, boletin, descripcion) VALUES (:nombre, :email, :sexo, :area_id, :boletin, :descripcion)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':sexo', $sexo);
            $stmt->bindParam(':area_id', $area_id);
            $stmt->bindParam(':boletin', $boletin);
            $stmt->bindParam(':descripcion', $descripcion);

            $stmt->execute();
            $empleado_id = $this->conn->lastInsertId();

            foreach ($roles as $rol_id) {
                $queryRol = "INSERT INTO empleado_rol (empleado_id, rol_id) VALUES (:empleado_id, :rol_id)";
                $stmtRol = $this->conn->prepare($queryRol);
                $stmtRol->bindParam(':empleado_id', $empleado_id);
                $stmtRol->bindParam(':rol_id', $rol_id);
                $stmtRol->execute();
            }

            return true;
        } catch (PDOException $exception) {
            error_log("Error al crear el empleado: " . $exception->getMessage()); // Loguear error
            echo "<script>alert('Error al crear el empleado. Inténtalo más tarde.');</script>";
            return false;
        }
    }

    // Método para obtener todos los empleados
    public function obtenerEmpleados()
    {
        try {
            $query = "SELECT e.*, GROUP_CONCAT(r.nombre SEPARATOR ', ') AS roles 
                      FROM empleados e
                      LEFT JOIN empleado_rol er ON e.id = er.empleado_id
                      LEFT JOIN roles r ON er.rol_id = r.id
                      GROUP BY e.id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log("Error al obtener empleados: " . $exception->getMessage());
            return [];
        }
    }

    // Método para obtener un solo empleado por ID
    public function obtenerEmpleadoPorId($id)
    {
        try {
            $query = "SELECT * FROM empleados WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log("Error al obtener el empleado por ID: " . $exception->getMessage());
            return false;
        }
    }

    // Método para actualizar un empleado
    public function actualizarEmpleado($id, $nombre, $email, $sexo, $area_id, $boletin, $descripcion, $roles)
    {
        try {
            // Verificar si el correo electrónico ya existe para otro empleado
            if ($this->existeCorreo($email, $id)) {
                echo "<script>alert('El correo electrónico ya está registrado. Inténtelo con otro.');</script>";
                return false;
            }

            $query = "UPDATE empleados SET nombre = :nombre, email = :email, sexo = :sexo, area_id = :area_id, boletin = :boletin, descripcion = :descripcion WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':sexo', $sexo);
            $stmt->bindParam(':area_id', $area_id);
            $stmt->bindParam(':boletin', $boletin);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':id', $id);

            $stmt->execute();

            // Actualizar roles
            $queryDeleteRoles = "DELETE FROM empleado_rol WHERE empleado_id = :empleado_id";
            $stmtDeleteRoles = $this->conn->prepare($queryDeleteRoles);
            $stmtDeleteRoles->bindParam(':empleado_id', $id);
            $stmtDeleteRoles->execute();

            foreach ($roles as $rol_id) {
                $queryRol = "INSERT INTO empleado_rol (empleado_id, rol_id) VALUES (:empleado_id, :rol_id)";
                $stmtRol = $this->conn->prepare($queryRol);
                $stmtRol->bindParam(':empleado_id', $id);
                $stmtRol->bindParam(':rol_id', $rol_id);
                $stmtRol->execute();
            }

            return true;
        } catch (PDOException $exception) {
            error_log("Error al actualizar el empleado: " . $exception->getMessage()); // Loguear error
            echo "<script>alert('Error al actualizar el empleado. Inténtalo más tarde.');</script>";
            return false;
        }
    }

    // Método para eliminar un empleado
    public function eliminarEmpleado($id)
    {
        try {
            // Primero eliminar los roles del empleado
            $queryDeleteRoles = "DELETE FROM empleado_rol WHERE empleado_id = :empleado_id";
            $stmtDeleteRoles = $this->conn->prepare($queryDeleteRoles);
            $stmtDeleteRoles->bindParam(':empleado_id', $id);
            $stmtDeleteRoles->execute();

            // Luego eliminar el empleado
            $query = "DELETE FROM empleados WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return true;
        } catch (PDOException $exception) {
            error_log("Error al eliminar el empleado: " . $exception->getMessage()); // Loguear error
            echo "<script>alert('Error al eliminar el empleado. Inténtalo más tarde.');</script>";
            return false;
        }
    }

    // Método para obtener los roles de un empleado
    public function obtenerRolesEmpleado($empleado_id)
    {
        try {
            $query = "SELECT rol_id FROM empleado_rol WHERE empleado_id = :empleado_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':empleado_id', $empleado_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            error_log("Error al obtener los roles del empleado: " . $exception->getMessage());
            return [];
        }
    }

    // Método para verificar si el correo electrónico ya existe
    private function existeCorreo($email, $id = null)
    {
        try {
            $query = "SELECT COUNT(*) FROM empleados WHERE email = :email";
            if ($id) {
                $query .= " AND id != :id"; // Excluir el empleado actual en la verificación de duplicados
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            if ($id) {
                $stmt->bindParam(':id', $id);
            }
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $exception) {
            error_log("Error al verificar el correo electrónico: " . $exception->getMessage());
            return false;
        }
    }
}
?>
