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

// Verificar que se reciba el ID de la factura
if (isset($_GET['id'])) {
    $factura_id = $_GET['id'];

    // Eliminar la factura de la base de datos
    $sql = "DELETE FROM facturas WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$factura_id]);

    echo "Factura eliminada con éxito.";
} else {
    die("ID de factura no proporcionado.");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Factura</title>
</head>
<body>
    <h2>Factura eliminada</h2>
    <p><a href="..\vendedor/facturacion.php">Regresar a Facturación</a></p>
</body>
</html>
