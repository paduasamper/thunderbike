<?php
session_start();

// Destruir la sesión
$_SESSION = array(); // Limpiar la sesión
session_destroy(); // Destruir la sesión

// Redirigir a logeo.php con parámetros
header('Location: index.php?msg=sesion_cerrada');
exit();
?>
