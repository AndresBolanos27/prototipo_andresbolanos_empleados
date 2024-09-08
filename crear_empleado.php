<?php
require_once 'config.php'; // Incluir configuración global
require_once 'clases/Empleado.php';
$empleado = new Empleado();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $sexo = $_POST['sexo'];
        $area_id = $_POST['area'];
        $boletin = isset($_POST['boletin']) ? 1 : 0;
        $descripcion = $_POST['descripcion'];
        $roles = isset($_POST['roles']) ? $_POST['roles'] : [];

        // Crear empleado y verificar si la creación fue exitosa
        if ($empleado->crearEmpleado($nombre, $email, $sexo, $area_id, $boletin, $descripcion, $roles)) {
            // Mostrar alerta de éxito usando JavaScript y redirigir
            echo "<script>
                    alert('Empleado creado exitosamente.');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        } else {
            // Mostrar alerta de error usando JavaScript
            echo "<script>
                    alert('Error al crear el empleado.');
                  </script>";
        }
    }
} catch (Exception $e) {
    // Manejar cualquier excepción lanzada
    error_log("Error en crear_empleado.php: " . $e->getMessage()); // Loguear el error
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
    <title>Crear Empleado</title>
    <!-- Incluye aquí el CSS necesario (Bootstrap) -->
    <link href="./Recursos/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <section class="vh-100 container pt-3">
        <h1 class="my-4 fw-normal fs-3">Crear Empleado</h1>
        <div class="alert alert-info" role="alert">
            Los campos con (*) son obligatorios
        </div>

        <form id="empleadoForm" method="POST" action="crear_empleado.php">
            <!-- Nombre completo -->
            <div class="row mb-3 align-items-center">
                <label for="nombre" class="col-sm-2 col-form-label">Nombre completo *</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre completo del empleado" required>
                </div>
            </div>

            <!-- Correo electrónico -->
            <div class="row mb-3 align-items-center">
                <label for="correo" class="col-sm-2 col-form-label">Correo electrónico *</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo electrónico" required>
                </div>
            </div>

            <!-- Sexo -->
            <div class="row mb-3 align-items-center">
                <label class="col-sm-2 col-form-label">Sexo *</label>
                <div class="col-sm-10">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sexo" id="masculino" value="M" required>
                        <label class="form-check-label" for="masculino">Masculino</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sexo" id="femenino" value="F" required>
                        <label class="form-check-label" for="femenino">Femenino</label>
                    </div>
                </div>
            </div>

            <!-- Área -->
            <div class="row mb-3 align-items-center">
                <label for="area" class="col-sm-2 col-form-label">Área *</label>
                <div class="col-sm-10">
                    <select class="form-select" id="area" name="area" required>
                        <option value="">Seleccione un área</option>
                        <option value="1">Administración</option>
                        <option value="2">Desarrollo</option>
                        <option value="3">Marketing</option>
                    </select>
                </div>
            </div>

            <!-- Descripción -->
            <div class="row mb-3 align-items-center">
                <label for="descripcion" class="col-sm-2 col-form-label">Descripción *</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción de la experiencia del empleado" required></textarea>
                </div>
            </div>

            <!-- Boletín -->
            <div class="row mb-3 align-items-center">
                <div class="col-sm-2">
                    <label class="form-check-label" for="boletin"> </label>
                </div>
                <div class="col-sm-10">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="boletin" name="boletin">
                        <label class="form-check-label" for="boletin">Deseo recibir boletín informativo</label>
                    </div>
                </div>
            </div>

            <!-- Roles -->
            <div class="row mb-3 align-items-center">
                <label class="col-sm-2 col-form-label">Roles *</label>
                <div class="col-sm-10">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roles[]" id="rol1" value="1" required>
                        <label class="form-check-label" for="rol1">Profesional de proyectos - Desarrollador</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roles[]" id="rol2" value="2">
                        <label class="form-check-label" for="rol2">Gerente estratégico</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roles[]" id="rol3" value="3">
                        <label class="form-check-label" for="rol3">Auxiliar administrativo</label>
                    </div>
                </div>
            </div>

            <!-- Botón de guardar -->
            <div class="row mb-3">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </section>

    <!-- jQuery y jQuery Validate -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <!-- Incluye aquí los scripts necesarios de Bootstrap -->
    <script src="./Recursos/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./Recursos//js/validacion.js"></script>

</body>
</html>
