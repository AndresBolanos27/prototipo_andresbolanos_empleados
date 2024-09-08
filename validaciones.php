<?php
// Función para validar el nombre (solo letras y espacios)
function validarNombre($nombre) {
    $nombre = trim($nombre);
    if (!preg_match("/^[a-zA-ZÁÉÍÓÚáéíóúñÑ\s]+$/", $nombre)) {
        return "Error: El nombre solo debe contener letras y espacios.";
    }
    return null; // Retorna null si es válido
}

// Función para validar el correo electrónico
function validarCorreo($correo) {
    $correo = trim($correo);
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        return "Error: Formato de correo electrónico no válido.";
    }
    return null; // Retorna null si es válido
}

// Función para validar el sexo (solo 'M' o 'F')
function validarSexo($sexo) {
    if (!in_array($sexo, ['M', 'F'])) {
        return "Error: Sexo no válido.";
    }
    return null; // Retorna null si es válido
}

// Función para validar el área (solo valores numéricos permitidos)
function validarArea($area) {
    if (!preg_match("/^[1-9][0-9]*$/", $area)) {
        return "Error: Área no válida.";
    }
    return null; // Retorna null si es válido
}

// Función para validar la descripción (permitir letras, números y espacios)
function validarDescripcion($descripcion) {
    $descripcion = trim($descripcion);
    if (!preg_match("/^[\w\sáéíóúÁÉÍÓÚñÑ.,?!-]*$/", $descripcion)) {
        return "Error: La descripción contiene caracteres no permitidos.";
    }
    return null; // Retorna null si es válido
}

// Función para validar los roles (al menos uno debe ser seleccionado)
function validarRoles($roles) {
    if (!is_array($roles) || empty($roles)) {
        return "Error: Debes seleccionar al menos un rol.";
    }
    foreach ($roles as $rol) {
        if (!preg_match("/^[1-9][0-9]*$/", $rol)) {
            return "Error: Rol no válido.";
        }
    }
    return null; // Retorna null si es válido
}

// Función para validar todos los datos y devolver los errores
function validarDatosEmpleado($nombre, $correo, $sexo, $area, $descripcion, $roles) {
    $errores = [];

    // Validar cada campo utilizando las funciones de validación
    $error = validarNombre($nombre);
    if ($error) $errores[] = $error;

    $error = validarCorreo($correo);
    if ($error) $errores[] = $error;

    $error = validarSexo($sexo);
    if ($error) $errores[] = $error;

    $error = validarArea($area);
    if ($error) $errores[] = $error;

    $error = validarDescripcion($descripcion);
    if ($error) $errores[] = $error;

    $error = validarRoles($roles);
    if ($error) $errores[] = $error;

    return $errores; // Retornar el array de errores
}
?>
