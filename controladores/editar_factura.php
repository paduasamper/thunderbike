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
    $sql_factura = "SELECT * FROM facturas WHERE id = ?";
    $stmt_factura = $pdo->prepare($sql_factura);
    $stmt_factura->execute([$factura_id]);
    $factura = $stmt_factura->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        die("Factura no encontrada.");
    }

    // Obtener la lista de clientes para el select
    $sql_clientes = "SELECT id, nombre FROM clientes";
    $stmt_clientes = $pdo->query($sql_clientes);
    $clientes = $stmt_clientes->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si se envió el formulario de edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cliente_id = $_POST['cliente_id'];
        $total = $_POST['total'];
        $fecha_factura = $_POST['fecha_factura'];
        $estado = $_POST['estado'];
        $productos = $_POST['productos'];

        if (!empty($cliente_id) && !empty($total) && !empty($fecha_factura) && !empty($estado) && !empty($productos)) {
            // Actualizar la factura en la base de datos
            $sql_update = "UPDATE facturas SET cliente_id = ?, total = ?, fecha_factura = ?, estado = ?, productos = ? WHERE id = ?";
            $stmt_update = $pdo->prepare($sql_update);

            try {
                $stmt_update->execute([$cliente_id, $total, $fecha_factura, $estado, $productos, $factura_id]);
                header("Location: ../vendedor/facturacion.php");
                exit();
            } catch (PDOException $e) {
                echo "Error al actualizar la factura: " . htmlspecialchars($e->getMessage());
            }
        } else {
            echo "Error: Todos los campos son obligatorios.";
        }
    }
} else {
    die("ID de factura no proporcionado.");
}

$pdo = null; // Cerrar la conexión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Factura</title>
</head>
<body>
    <h2>Editar Factura</h2>
    <form action="editar_factura.php?id=<?= htmlspecialchars($factura_id) ?>" method="POST">
        <label for="cliente_id">Cliente:</label>
        <select name="cliente_id" id="cliente_id" required>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= htmlspecialchars($cliente['id']) ?>" <?= $cliente['id'] == $factura['cliente_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cliente['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="total">Total:</label>
        <input type="number" name="total" id="total" value="<?= htmlspecialchars($factura['total']) ?>" step="0.01" required><br>

        <label for="fecha_factura">Fecha de Factura:</label>
        <input type="date" name="fecha_factura" id="fecha_factura" value="<?= htmlspecialchars($factura['fecha_factura']) ?>" required><br>

        <label for="estado">Estado:</label>
        <select name="estado" id="estado" required>
            <option value="Pendiente" <?= $factura['estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="Credito" <?= $factura['estado'] == 'Credito' ? 'selected' : '' ?>>Credito</option>
            <option value="Cancelada" <?= $factura['estado'] == 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
        </select><br>

        <label for="productos">Productos:</label>
        <textarea name="productos" id="productos" required><?= htmlspecialchars($factura['productos']) ?></textarea><br>

        <button type="submit">Guardar Cambios</button>
    </form>
    <a href="../vendedor/facturacion.php">Regresar</a>
</body>
</html>
