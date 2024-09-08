<?php
require_once 'config.php'; // Incluir configuración global para manejo de errores
require_once 'clases/Empleado.php';
$empleado = new Empleado();

// Obtener el ID del empleado de la URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

try {
    // Verificar si se envió el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $sexo = $_POST['sexo'];
        $area_id = $_POST['area'];
        $boletin = isset($_POST['boletin']) ? 1 : 0;
        $descripcion = $_POST['descripcion'];
        $roles = isset($_POST['roles']) ? $_POST['roles'] : [];

        // Actualizar el empleado
        if ($empleado->actualizarEmpleado($id, $nombre, $email, $sexo, $area_id, $boletin, $descripcion, $roles)) {
            // Mostrar alerta de éxito usando JavaScript y redirigir
            echo "<script>
                    alert('Empleado actualizado exitosamente.');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        } else {
            // Mostrar alerta de error usando JavaScript
            echo "<script>
                    alert('Error al actualizar el empleado.');
                  </script>";
        }
    }

    // Obtener los datos del empleado actual
    $empleadoData = $empleado->obtenerEmpleadoPorId($id);
    $empleadoRoles = array_column($empleado->obtenerRolesEmpleado($id), 'rol_id'); // Asumiendo que tienes un método para obtener roles
} catch (Exception $e) {
    // Manejar cualquier excepción lanzada
    error_log("Error en editar_empleado.php: " . $e->getMessage()); // Loguear el error
    echo "<script>
            alert('Inténtalo más tarde.');
            window.location.href = 'index.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Empleado</title>
    <!-- Incluye aquí el CSS necesario (Bootstrap) -->
    <link href="./Recursos/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<section class="vh-100 container pt-3">
    <h1 class="my-4 fw-normal fs-3">Editar Empleado</h1>

    <div class="alert alert-info" role="alert">
        Los campos con (*) son obligatorios
    </div>

    <form action="editar_empleado.php?id=<?php echo $id; ?>" method="POST">
        <!-- Nombre completo -->
        <div class="row mb-3 align-items-center">
            <label for="nombre" class="col-sm-2 col-form-label">Nombre completo *</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($empleadoData['nombre']); ?>" required>
            </div>
        </div>

        <!-- Correo electrónico -->
        <div class="row mb-3 align-items-center">
            <label for="email" class="col-sm-2 col-form-label">Correo electrónico *</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($empleadoData['email']); ?>" required>
            </div>
        </div>

        <!-- Sexo -->
        <div class="row mb-3 align-items-center">
            <label class="col-sm-2 col-form-label">Sexo *</label>
            <div class="col-sm-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sexo" id="masculino" value="M" <?php echo $empleadoData['sexo'] == 'M' ? 'checked' : ''; ?> required>
                    <label class="form-check-label" for="masculino">Masculino</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sexo" id="femenino" value="F" <?php echo $empleadoData['sexo'] == 'F' ? 'checked' : ''; ?> required>
                    <label class="form-check-label" for="femenino">Femenino</label>
                </div>
            </div>
        </div>

        <!-- Área -->
        <div class="row mb-3 align-items-center">
            <label for="area" class="col-sm-2 col-form-label">Área *</label>
            <div class="col-sm-10">
                <select class="form-select" id="area" name="area" required>
                    <option value="1" <?php echo $empleadoData['area_id'] == 1 ? 'selected' : ''; ?>>Administración</option>
                    <option value="2" <?php echo $empleadoData['area_id'] == 2 ? 'selected' : ''; ?>>Desarrollo</option>
                    <option value="3" <?php echo $empleadoData['area_id'] == 3 ? 'selected' : ''; ?>>Marketing</option>
                </select>
            </div>
        </div>

        <!-- Descripción -->
        <div class="row mb-3 align-items-center">
            <label for="descripcion" class="col-sm-2 col-form-label">Descripción *</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo htmlspecialchars($empleadoData['descripcion']); ?></textarea>
            </div>
        </div>

        <!-- Boletín -->
        <div class="row mb-3 align-items-center">
            <div class="col-sm-2">
                <label class="form-check-label" for="boletin"></label>
            </div>
            <div class="col-sm-10">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="boletin" name="boletin" <?php echo $empleadoData['boletin'] == 1 ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="boletin">Deseo recibir boletín informativo</label>
                </div>
            </div>
        </div>

        <!-- Roles -->
        <div class="row mb-3 align-items-center">
            <label class="col-sm-2 col-form-label">Roles *</label>
            <div class="col-sm-10">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="roles[]" id="rol1" value="1" <?php echo in_array(1, $empleadoRoles) ? 'checked' : ''; ?> required>
                    <label class="form-check-label" for="rol1">Desarrollador</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="roles[]" id="rol2" value="2" <?php echo in_array(2, $empleadoRoles) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="rol2">Gerente</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="roles[]" id="rol3" value="3" <?php echo in_array(3, $empleadoRoles) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="rol3">Auxiliar Administrativo</label>
                </div>
            </div>
        </div>

        <!-- Botón de actualizar -->
        <div class="row mb-3">
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
    </form>
</section>

<!-- jQuery y jQuery Validate -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<!-- Incluye aquí los scripts necesarios de Bootstrap -->
<script src="./Recursos/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="./Recursos/js/validacion.js"></script>
</body>
</html>
