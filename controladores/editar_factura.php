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

    // Obtener los datos de la factura para mostrarlos en el formulario
    $sql = "SELECT * FROM facturas WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$factura_id]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        die("Factura no encontrada.");
    }
} else {
    die("ID de factura no proporcionado.");
}

// Actualizar la factura cuando se envíe el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_POST['cliente_id'];
    $total = $_POST['total'];
    $fecha_factura = $_POST['fecha_factura'];

    $sql = "UPDATE facturas SET cliente_id = ?, total = ?, fecha_factura = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cliente_id, $total, $fecha_factura, $factura_id]);

    echo "Factura actualizada con éxito.";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Factura</title>
</head>
<body>
    <h2>Editar Factura</h2>
    <form method="POST">
        <label>ID Cliente:</label>
        <input type="text" name="cliente_id" value="<?php echo $factura['cliente_id']; ?>" required><br>
        
        <label>Total:</label>
        <input type="text" name="total" value="<?php echo $factura['total']; ?>" required><br>
        
        <label>Fecha de Factura:</label>
        <input type="date" name="fecha_factura" value="<?php echo $factura['fecha_factura']; ?>" required><br>
        
        <button type="submit">Guardar Cambios</button>
    </form>
    <a href="..\vendedor/facturacion.php">Regresar a Facturación</a>
</body>
</html>

