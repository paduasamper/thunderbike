<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header('location: index.php');
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Mecánico</title>
</head>
<body>
  <h1>Bienvenido, <?php echo $username; ?>!</h1>
  <p>Tu rol es: <?php echo $role; ?></p>

  <!-- Contenido para el mecánico -->
  <h2>Panel de Mecánico</h2>
  <p>Opciones para trabajos de mantenimiento.</p>

  <a href="logout.php">Cerrar sesión</a>
</body>
</html>
