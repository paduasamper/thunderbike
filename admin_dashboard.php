<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header('location: index.php'); // Redirigir al login si no ha iniciado sesión
    exit();
}

// Obtener el nombre de usuario y el rol desde la sesión
$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administrador</title>
  <!-- Añadir tus estilos aquí -->
</head>
<body>
  <h1>Bienvenido, <?php echo $username; ?>!</h1>
  <p>Tu rol es: <?php echo $role; ?></p>
  
  <!-- Contenido exclusivo para el administrador -->
  <h2>Panel de Administración</h2>
  <p>Administrar usuarios, productos y más.</p>

  <a href="logout.php">Cerrar sesión</a>
</body>
</html>
