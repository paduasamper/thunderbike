<?php
ob_start(); // Iniciar el almacenamiento en búfer de salida

// Conexión a la base de datos
$dsn = 'mysql:host=localhost;dbname=thunderbike;charset=utf8';
$username = 'root';
$password = '';

try {
    // Establecer la conexión
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . htmlspecialchars($e->getMessage()));
    $result_facturas = [];
    $clientes = [];
}

// Obtener las facturas existentes
$sql_facturas = 'SELECT f.id, c.nombre AS nombre_cliente, f.total, f.fecha_factura, f.estado, f.productos, cantidad
                 FROM facturas AS f 
                 JOIN clientes AS c ON f.cliente_id = c.id';
$stmt_facturas = $pdo->query($sql_facturas);
$result_facturas = $stmt_facturas->fetchAll(PDO::FETCH_ASSOC);

// Obtener todos los clientes
$sql_clientes = "SELECT id, nombre FROM clientes";
$stmt_clientes = $pdo->query($sql_clientes);
$clientes = $stmt_clientes->fetchAll(PDO::FETCH_ASSOC);

// Verificar si se ha enviado el formulario para agregar una nueva factura
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>Datos enviados: "; print_r($_POST); echo "</pre>"; // Depuración de datos enviados

    if (isset($_POST['cliente_id'], $_POST['total'], $_POST['fecha_factura'], $_POST['estado'], $_POST['productos'])) {
        $cliente_id = $_POST['cliente_id'];
        $total = $_POST['total'];
        $fecha_factura = $_POST['fecha_factura'];
        $estado = $_POST['estado'];
        $productos = json_encode(array_filter($_POST['productos'], 'trim')); // Filtrar productos vacíos y convertir a JSON
        $cantidad = $_POST['cantidad'];

        // Validar datos antes de insertar
        if (!empty($cliente_id) && !empty($total) && !empty($fecha_factura) && !empty($estado) && !empty($productos)) {
            $sql_insert = "INSERT INTO facturas (cliente_id, total, fecha_factura, estado, productos, cantidad) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
        
            try {
                $stmt_insert->execute([$cliente_id, $total, $fecha_factura, $estado, $productos, $cantidad]);
                echo "Factura agregada exitosamente.<br>";
                header("Location: facturacion.php"); // Redireccionar para evitar reenvíos
                exit();
            } catch (PDOException $e) {
                echo "Error al agregar la factura: " . htmlspecialchars($e->getMessage());
            }
        } else {
            echo "Error: Todos los campos son obligatorios y los productos no pueden estar vacíos.<br>";
        }
    }
}

$pdo = null; // Cerrar la conexión
ob_end_flush(); // Liberar el almacenamiento en búfer
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturación</title>
    <style>
        .navtop {
            background: #333;
            padding: 10px 20px;
            color: white;
            text-align: center;
        }
        .navtop .button {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .scrollable-table {
            max-height: 400px;
            overflow-y: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background: #f4f4f4;
        }
        button, select, input {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background: #007bff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
                /* Estilos globales */
                body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: black;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        /* Barra de navegación */
        .navtop {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .navtop a {
            color: white;
            text-decoration: none;
            margin: 5px 10px;
        }

        .navtop a:hover {
            color: goldenrod;
        }

        .container {
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1200px;
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Botones */
        button, form button {
            padding: 8px 12px;
            background-color: gold;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover, form button:hover {
            background-color: wheat;
        }

        /* Modal */
        #modal-editar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        #modal-editar .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .navtop {
                flex-direction: column;
                text-align: center;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            th, td {
                white-space: nowrap;
            }

            .container {
                width: 95%;
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.5em;
            }

            button, form button {
                padding: 6px 10px;
                font-size: 0.9em;
            }
        }
        /* Estilo para el fondo de video */
.video-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
}

#background-video {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    z-index: -1;
}

/* Contenido encima del video */
.content {
    position: relative;
    z-index: 1;
}

/* Ajustes para que el contenido siga siendo legible */
body {
    background: rgba(0, 0, 0, 0.5); /* Capa de semitransparencia sobre el video */
    color: black;
}
    </style>
    <script>
        function agregarProducto() {
            const productosList = document.getElementById('productos-list');
            const productoItem = document.createElement('div');
            productoItem.className = 'producto-item';
            productoItem.innerHTML = `
                <input type="text" name="productos[]" placeholder="Producto" required>
                <button type="button" onclick="eliminarProducto(this)">Eliminar</button>
            `;
            productosList.appendChild(productoItem);
        }

        function eliminarProducto(btn) {
            const productoItem = btn.parentElement;
            productoItem.remove();
        }
    </script>
</head>
<body>
<div class="video-background">
        <video autoplay muted loop id="background-video">
            <source src="ruta-del-video.mp4" type="video/mp4">
        </video>
    </div>
<nav class="navtop">
        <div>
            <a href="vendedor_dashboard.php" class="button">Inicio</a>
            <a href="perfil.php" class="button">Perfil</a>
            <a href="client.php" class="button">Clientes</a>
            <a href="productos.php" class="button">Productos</a>
            <a href="ventas.php" class="button">Ventas</a>
            <a href="facturacion.php" class="button">Facturación</a>
        </div>
    </nav>
    <div class="container">
        <h2>Agregar Nueva Factura</h2>
        <form action="facturacion.php" method="POST">
            <label for="cliente_id">Cliente:</label>
            <select name="cliente_id" id="cliente_id" required>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= htmlspecialchars($cliente['id']) ?>">
                        <?= htmlspecialchars($cliente['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <label for="productos">Productos:</label>
            <div id="productos-list" class="productos-list">
                <div class="producto-item">
                    <input type="text" name="productos[]" placeholder="Producto" required>
                </div>
            </div>
            <button type="button" onclick="agregarProducto()">Agregar Producto</button><br>

            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" step="0.01" required><br>

            <label for="total">Total:</label>
            <input type="number" name="total" id="total" step="0.01" required><br>

            <label for="fecha_factura">Fecha de Factura:</label>
            <input type="date" name="fecha_factura" id="fecha_factura" required><br>

            <label for="estado">Estado:</label>
            <select name="estado" id="estado" required>
                <option value="Pendiente">Pendiente</option>
                <option value="Credito">Credito</option>
                <option value="Cancelada">Cancelada</option>
            </select><br>

            <button type="submit">Guardar Factura</button>
        </form>

        <div class="container">
        <h2>Facturas Realizadas</h2>
        <div class="scrollable-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Cantidad</th>
                    <th>Productos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($result_facturas)): ?>
                <?php foreach ($result_facturas as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['nombre_cliente']) ?></td>
                        <td><?= htmlspecialchars($row['total']) ?></td>
                        <td><?= htmlspecialchars($row['fecha_factura']) ?></td>
                        <td><?= htmlspecialchars($row['estado']) ?></td>
                        <td><?= htmlspecialchars($row['cantidad']) ?></td>
                        <td><?= htmlspecialchars($row['productos']) ?></td>
                        <td>
                                <a href="../controladores/editar_factura.php?id=<?= htmlspecialchars($row["id"]) ?>" class="btn">Editar</a>
                                <a href="../controladores/eliminar_factura.php?id=<?= htmlspecialchars($row["id"]) ?>" class="btn" onclick="return confirm('¿Estás seguro de que deseas eliminar esta factura?');">Eliminar</a>
                                <a href="generar_pdf.php?id=<?= htmlspecialchars($row["id"]) ?>" class="btn">Generar PDF</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7">No se encontraron facturas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>


