<?php 
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header('location: inicio.php');
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Redirigir a la página de ventas si el rol es vendedor
if ($role == 'vendedor') {
    header('Location: ventas_vendedor.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Vendedor</title>
</head>
<body>
  <h1>Bienvenido, <?php echo htmlspecialchars($username); ?>!</h1>
  <p>Tu rol es: <?php echo htmlspecialchars($role); ?></p>

  <!-- Contenido para el vendedor -->
  <h2>Panel de Vendedor</h2>
  <p>Administrar ventas y productos.</p>

  <a href="ventas_vendedor.php">Ir al Panel de Ventas</a>
  <a href="logout.php">Cerrar sesión</a>
</body>
</html> 

