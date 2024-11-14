<?php
ob_start(); // Iniciar el almacenamiento en búfer de salida

// Conexión a la base de datos (ya existente en tu código)
$dsn = 'mysql:host=localhost;dbname=thunderbike';
$username = 'root';      // Cambia 'tu_usuario' por el nombre de usuario correcto
$password = ''; 

try {
    // Establecer la conexión
    $pdo = new PDO($dsn, $username, $password);
    // Configurar el modo de error de PDO para que lance excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Definir la consulta SQL
    $sql_facturas = 'SELECT f.id, c.nombre, f.total FROM facturas AS f JOIN clientes AS c ON f.cliente_id = c.id';

    // Ejecutar la consulta
    $resultado = $pdo->query($sql_facturas);

    // Procesar los resultados
    foreach ($resultado as $fila) {
        echo "ID: " . $fila['id'] . " - Cliente: " . $fila['nombre'] . " - Total: " . $fila['total'] . "<br>";
    }

} catch (PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
}
// Consulta para obtener las facturas
$query = 'SELECT f.id, c.nombre, f.total FROM facturas AS f JOIN clientes AS c ON f.cliente_id = c.id';

$stmt_facturas = $pdo->query($sql_facturas);
$result_facturas = $stmt_facturas->fetchAll(PDO::FETCH_ASSOC);

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
        <h2>Facturas Realizadas</h2>
        <div class="scrollable-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha de Factura</th>
                        <th>Detalles</th>
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
                        echo "<td>" . htmlspecialchars($row["detalles"]) . "</td>";
                        echo "<td>
                                <a href='..\controladores/editar_factura.php?id=" . htmlspecialchars($row["id"]) . "' class='btn'>Editar</a>
                                <a href='..\controladores/eliminar_factura.php?id=" . htmlspecialchars($row["id"]) . "' class='btn' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta factura?\");'>Eliminar</a>
                                <a href='generar_pdf.php?id=" . htmlspecialchars($row["id"]) . "' class='btn'>Generar PDF</a>
                                </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No se encontraron facturas.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
