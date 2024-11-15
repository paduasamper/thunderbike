<?php
ob_start(); // Iniciar el almacenamiento en búfer de salida

// Conexión a la base de datos
$dsn = 'mysql:host=localhost;dbname=thunderbike';
$username = 'root';
$password = ''; 

try {
    // Establecer la conexión
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Definir la consulta SQL con los campos disponibles
    $sql_facturas = 'SELECT f.id, c.nombre AS nombre_cliente, f.total, f.fecha_factura, f.estado 
                     FROM facturas AS f 
                     JOIN clientes AS c ON f.cliente_id = c.id';

    // Ejecutar la consulta
    $stmt_facturas = $pdo->query($sql_facturas);
    $result_facturas = $stmt_facturas->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
    $result_facturas = []; // Asignar un array vacío en caso de error
}

// Verificar si se ha enviado el formulario para agregar una nueva factura
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente_id'], $_POST['total'], $_POST['fecha_factura'], $_POST['estado'])) {
    $cliente_id = $_POST['cliente_id'];
    $total = $_POST['total'];
    $fecha_factura = $_POST['fecha_factura'];
    $estado = $_POST['estado'];

    // Insertar la nueva factura en la base de datos
    $sql_insert = "INSERT INTO facturas (cliente_id, total, fecha_factura, estado) VALUES (?, ?, ?, ?)";
    $stmt_insert = $pdo->prepare($sql_insert);

    try {
        $stmt_insert->execute([$cliente_id, $total, $fecha_factura, $estado]);
        header("Location: facturacion.php"); // Redireccionar a la misma página para evitar reenvío de formulario
        exit();
    } catch (PDOException $e) {
        echo "Error al agregar la factura: " . $e->getMessage();
    }
}

$pdo = null; // Cerrar la conexión después de procesar la consulta
ob_end_flush(); // Liberar el almacenamiento en búfer de salida y enviar el contenido al navegador
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturación</title>
    <style>
        /* ... copia los estilos de tu archivo ventas.php o define estilos similares */
    </style>
</head>
<body>
    <nav class="navtop">
        <div>
            <div class="container">
                <div class="button-container">
                    <a href="vendedor_dashboard.php" class="button">Inicio</a>
                    <a href="perfil.php" class="button">Perfil</a>
                    <a href="clientes.php" class="button">Clientes</a>
                    <a href="productos.php" class="button">Productos</a>
                    <a href="ventas.php" class="button">Ventas</a>
                    <a href="facturacion.php" class="button">Facturación</a>
                </div>
            </div>
        </div>
    </nav>

    <video id="background-video" autoplay muted loop>
        <source src="..\img/facturacion.mp4" type="video/mp4">
        Tu navegador no admite la etiqueta de video.
    </video>

    <div class="container">
        <h2>Agregar Nueva Factura</h2>
        <form action="facturacion.php" method="POST">
            <label for="cliente_id">Cliente ID:</label>
            <input type="number" name="cliente_id" id="cliente_id" required><br>

            <label for="total">Total:</label>
            <input type="number" name="total" id="total" step="0.01" required><br>

            <label for="fecha_factura">Fecha de Factura:</label>
            <input type="date" name="fecha_factura" id="fecha_factura" required><br>

            <label for="estado">Estado:</label>
            <select name="estado" id="estado" required>
                <option value="Pendiente">Pendiente</option>
                <option value="Pagada">Pagada</option>
                <option value="Cancelada">Cancelada</option>
            </select><br>

            <button type="submit">Agregar Factura</button>
        </form>
    </div>

    <div class="container">
        <h2>Facturas Realizadas</h2>
        <div class="scrollable-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha de Factura</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if (is_array($result_facturas) && count($result_facturas) > 0) {
                    foreach ($result_facturas as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["nombre_cliente"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["total"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["fecha_factura"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["estado"]) . "</td>";
                        echo "<td>
                                <a href='..\controladores/editar_factura.php?id=" . htmlspecialchars($row["id"]) . "' class='btn'>Editar</a>
                                <a href='..\controladores/eliminar_factura.php?id=" . htmlspecialchars($row["id"]) . "' class='btn' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta factura?\");'>Eliminar</a>
                                <a href='generar_pdf.php?id=" . htmlspecialchars($row["id"]) . "' class='btn'>Generar PDF</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No se encontraron facturas.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>



