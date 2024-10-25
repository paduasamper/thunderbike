<?php
// Conexión a la base de datos
$dsn = 'mysql:host=localhost;dbname=thunderbike;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

// Obtener el ID de la venta a eliminar
$id = $_GET['id'] ?? 0;

// Eliminar la venta
$sql_delete = "DELETE FROM ventas WHERE id = :id";
$stmt_delete = $pdo->prepare($sql_delete);
$stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);

if ($stmt_delete->execute()) {
    header("Location: ventas.php");
    exit();
} else {
    echo "Error: No se pudo eliminar la venta.";
}

$pdo = null; // Cerrar la conexión después de procesar la consulta
?>
