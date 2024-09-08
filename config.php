<?php
// Ocultar errores de PHP al usuario final
ini_set('display_errors', 0); // No mostrar errores al usuario
error_reporting(E_ALL); // Reportar todos los errores para el registro

// Manejador de errores para errores no fatales
function manejadorErrores($errno, $errstr, $errfile, $errline) {
    // Loguear el error para fines de depuración (opcional)
    error_log("Error: [$errno] $errstr en $errfile en la línea $errline");

    // Mostrar una alerta genérica al usuario
    echo "<script>alert('Inténtalo más tarde.');</script>";
    echo "<script>window.location.href = 'index.php';</script>"; // Redirigir después de la alerta
    exit(); // Detener la ejecución del script
}

// Configurar el manejador de errores
set_error_handler('manejadorErrores');

// Manejador de errores fatales
function manejadorErroresFatales() {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE || $error['type'] === E_COMPILE_ERROR || $error['type'] === E_CORE_ERROR)) {
        // Loguear el error fatal para fines de depuración (opcional)
        error_log("Error fatal: " . $error['message'] . " en " . $error['file'] . " en la línea " . $error['line']);

        // Mostrar una alerta genérica al usuario
        echo "<script>alert('Inténtalo más tarde.');</script>";
        echo "<script>window.location.href = 'index.php';</script>"; // Redirigir después de la alerta
        exit(); // Detener la ejecución del script
    }
}

// Configurar el manejador de errores fatales
register_shutdown_function('manejadorErroresFatales');
?>
