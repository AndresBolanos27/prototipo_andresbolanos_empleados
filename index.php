<?php
require_once 'clases/Empleado.php';

// Crear una instancia de la clase Empleado
$empleado = new Empleado();

// Verificar si se ha solicitado la eliminación de un empleado
if (isset($_GET['action']) && $_GET['action'] == 'eliminar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    
    if ($empleado->eliminarEmpleado($id)) {
        echo "<div class='alert alert-success'>Empleado eliminado exitosamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar el empleado.</div>";
    }
}

$conn = $empleado->getConexion();

// Consulta para obtener los datos de los empleados junto con sus áreas
$sql = "SELECT empleados.id, empleados.nombre, empleados.email, empleados.sexo, areas.nombre AS area, 
        IF(empleados.boletin = 1, 'Sí', 'No') AS boletin
        FROM empleados
        LEFT JOIN areas ON empleados.area_id = areas.id";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Empleado</title>
    <link href="./Recursos/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de Empleados</h2>
        <a href="./crear_empleado.php" class="btn btn-primary"><i class="fas fa-plus"></i> Crear</a>
    </div>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><i class="fas fa-user"></i> Nombre</th>
                <th><i class="fas fa-envelope"></i> Email</th>
                <th><i class="fas fa-venus-mars"></i> Sexo</th>
                <th><i class="fas fa-briefcase"></i> Área</th>
                <th><i class="fas fa-bell"></i> Boletín</th>
                <th><i class="fas fa-edit"></i> Editar</th>
                <th><i class="fas fa-trash-alt"></i> Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($result) > 0): ?>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo $row['sexo'] == 'M' ? 'Masculino' : 'Femenino'; ?></td>
                        <td><?php echo htmlspecialchars($row['area']); ?></td>
                        <td><?php echo htmlspecialchars($row['boletin']); ?></td>
                        <!-- Enlace de edición con ID de empleado -->
                        <td><a href="editar_empleado.php?id=<?php echo $row['id']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Editar</a></td>

                        <!-- Botón de eliminación con formulario -->
                        <td>
                            <form action="index.php?action=eliminar" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este empleado?');">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No hay empleados registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn = null;
?>
