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
}

// Filtrar por cliente (ID o nombre)
$searchQuery = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $searchQuery = "WHERE c.nombre LIKE :search OR c.id = :searchExact";
}

// Obtener las facturas existentes con búsqueda
$sql_facturas = "SELECT f.id, c.nombre AS nombre_cliente, f.total, f.fecha_factura, f.estado, f.productos, f.cantidad, f.vendedor
                 FROM facturas AS f 
                 JOIN clientes AS c ON f.cliente_id = c.id
                 $searchQuery";
$stmt_facturas = $pdo->prepare($sql_facturas);

if ($searchQuery) {
    $stmt_facturas->execute([':search' => "%$search%", ':searchExact' => $search]);
} else {
    $stmt_facturas->execute();
}

$result_facturas = $stmt_facturas->fetchAll(PDO::FETCH_ASSOC);

// Número de resultados por página
$resultsPerPage = 3;

// Calcular la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $resultsPerPage;

// Consulta SQL
$sql_facturas = "SELECT f.id, c.nombre AS nombre_cliente, f.total, f.fecha_factura, f.estado, f.productos, f.cantidad, f.vendedor
                 FROM facturas AS f 
                 JOIN clientes AS c ON f.cliente_id = c.id
                 $searchQuery
                 LIMIT :limit OFFSET :offset";

// Preparar la consulta
$stmt_facturas = $pdo->prepare($sql_facturas);

// Vincular LIMIT y OFFSET
$stmt_facturas->bindValue(':limit', $resultsPerPage, PDO::PARAM_INT);
$stmt_facturas->bindValue(':offset', $start, PDO::PARAM_INT);

// Ejecutar según la presencia de $searchQuery
if ($searchQuery) {
    // Asegúrate de pasar los parámetros requeridos
    $stmt_facturas->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $stmt_facturas->bindValue(':searchExact', $search, PDO::PARAM_STR);
    $stmt_facturas->execute();
} else {
    $stmt_facturas->execute();
}

// Obtener resultados
$result_facturas = $stmt_facturas->fetchAll(PDO::FETCH_ASSOC);


// Obtener el total de facturas para la paginación
$sql_count = "SELECT COUNT(*) FROM facturas AS f JOIN clientes AS c ON f.cliente_id = c.id $searchQuery";
$stmt_count = $pdo->prepare($sql_count);
if ($searchQuery) {
    $stmt_count->execute([':search' => "%$search%", ':searchExact' => $search]);
} else {
    $stmt_count->execute();
}
$totalRecords = $stmt_count->fetchColumn();
$totalPages = ceil($totalRecords / $resultsPerPage);


// Obtener todos los clientes
$sql_clientes = "SELECT id, nombre FROM clientes";
$stmt_clientes = $pdo->query($sql_clientes);
$clientes = $stmt_clientes->fetchAll(PDO::FETCH_ASSOC);

// Verificar si se ha enviado el formulario para agregar una nueva factura
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cliente_id'], $_POST['total'], $_POST['fecha_factura'], $_POST['estado'], $_POST['productos'])) {
        $cliente_id = $_POST['cliente_id'];
        $total = $_POST['total'];
        $fecha_factura = $_POST['fecha_factura'];
        $estado = $_POST['estado'];
        $productos = json_encode(array_filter($_POST['productos'], 'trim')); // Filtrar productos vacíos y convertir a JSON
        $cantidad = $_POST['cantidad'];
        $vendedor = $_POST['vendedor'];

        // Validar que la fecha no sea superior al día actual
        $fechaActual = date('Y-m-d');
        if ($fecha_factura > $fechaActual) {
            echo "Error: La fecha no puede ser posterior al día actual.<br>";
        } else {
            // Validar datos antes de insertar
            if (!empty($cliente_id) && !empty($total) && !empty($fecha_factura) && !empty($estado) && !empty($productos)) {
                $sql_insert = "INSERT INTO facturas (cliente_id, total, fecha_factura, estado, productos, cantidad, vendedor) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_insert = $pdo->prepare($sql_insert);

                try {
                    $stmt_insert->execute([$cliente_id, $total, $fecha_factura, $estado, $productos, $cantidad, $vendedor]);
                    echo "Factura agregada exitosamente.<br>";
                    header("Location: facturacion.php"); // Redireccionar para evitar reenvíos
                    exit();
                } catch (PDOException $e) {
                    echo "Error al agregar la factura: " . htmlspecialchars($e->getMessage());
                }
            }
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
    <link href="bootstrap-4.6.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Facturación</title>
    <link rel="icon" type="image/png" href="../img/thunderbikes.png">
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa; /* Fondo claro para contraste */
    color: #333;
}

.navtop {
    background-color: #343a40; /* Color oscuro para la barra de navegación */
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navtop a {
    color: white;
    text-decoration: none;
    margin: 0 15px;
    font-weight: bold;
}

.navtop a:hover {
    color: #ffc107; /* Amarillo para el hover */
}

.container {
    max-width: 1200px;
    margin: 20px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra suave */
}

h1, h2 {
    text-align: center;
    color: while;
}

button, select, input {
    display: block;
    width: 100%;
    margin: 10px 0;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

button {
    background-color: #007bff; /* Azul para botones */
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3; /* Azul más oscuro al pasar el mouse */
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

th, td {
    padding: 15px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background-color: #343a40;
    color: white;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1; /* Efecto de hover en las filas */
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination a {
    padding: 8px 16px;
    margin: 0 5px;
    text-decoration: none;
    color: #007bff;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.pagination a:hover {
    background-color: #007bff;
    color: white;
}

.pagination .active {
    background-color: #007bff;
    color: white;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input, .form-group select {
    width: calc(100% - 20px);
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.button-group {
    display: flex;
    justify-content: space-between;
    gap: 10px;
}

.button-group button {
    flex: 1;
    padding: 10px;
}

    </style>
</head>
<body>
<nav class="navtop">
<div>
            <img src="../img/thunderbikes.png" alt="Thunderbikes" style="width: 50px; height: 50px;">
            <h1>THUNDERBIKE</h1>
        </div>
        <div>
        <a href="perfil.php" id="perfilBtn" class="button">Perfil</a>
        <a href="vendedor_dashboard.php" id="indexBtn" class="button">Inicio</a>
        <a href="client.php" id="clientesBtn" class="button">Clientes</a>
        <a href="productos.php" id="productosBtn" class="button">Productos</a>
        <a href="facturacion.php" id="facturacionBtn" class="button">Facturacion</a>
        </div>
    </nav>
<div class="container">
    <h2>Buscar Facturas</h2>
    <form method="GET" action="facturacion.php">
        <input type="text" name="search" placeholder="Buscar por cliente o ID" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit">Buscar</button>
    </form>

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
        <div id="productos-list">
            <div>
                <input type="text" name="productos[]" placeholder="Producto" required>
            </div>
        </div>
        <button type="button" onclick="agregarProducto()">Agregar Producto</button><br>

        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" step="1" required><br>

        <label for="total">Total:</label>
        <input type="number" name="total" id="total" step="0.01" required><br>

        <?php
        $minDate = date('Y-m-d', strtotime('-1 month')); // Hace un mes
        $maxDate = date('Y-m-d'); // Fecha actual
        ?>

        <label for="fecha_factura">Fecha de Factura:</label>
        <input 
    type="date" 
    name="fecha_factura" 
    id="fecha_factura" 
    required 
    value="<?= date('Y-m-d') ?>" 
    max="<?= date('Y-m-d') ?>"
> <br>

        <label for="estado">Estado:</label>
        <select name="estado" id="estado" required>
            <option value="Pendiente">Pendiente</option>
            <option value="Credito">Credito</option>
            <option value="Cancelada">Cancelada</option>
        </select><br>

        <label for="vendedor">Vendedor:</label>
        <input type="text" name="vendedor" id="vendedor" required><br>

        <button type="submit">Guardar Factura</button>
    </form>

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
                    <th>Vendedor</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($result_facturas as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_cliente']) ?></td>
                    <td><?= htmlspecialchars($row['total']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_factura']) ?></td>
                    <td><?= htmlspecialchars($row['estado']) ?></td>
                    <td><?= htmlspecialchars($row['cantidad']) ?></td>
                    <td><?= htmlspecialchars($row['productos']) ?></td>
                    <td><?= htmlspecialchars($row['vendedor']) ?></td>
                    <td>
                                <a href="../controladores/editar_factura.php?id=<?= htmlspecialchars($row["id"]) ?>" class="btn">Editar</a>
                                <a href="../controladores/eliminar_factura.php?id=<?= htmlspecialchars($row["id"]) ?>" class="btn" onclick="return confirm('¿Estás seguro de que deseas eliminar esta factura?');">Eliminar</a>
                                <a href="generar_pdf.php?id=<?= htmlspecialchars($row["id"]) ?>" class="btn">Generar PDF</a>
                        </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=1">Primera</a>
        <a href="?page=<?= $page - 1 ?>">Anterior</a>
    <?php endif; ?>

    <span>Página <?= $page ?> de <?= $totalPages ?></span>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>">Siguiente</a>
        <a href="?page=<?= $totalPages ?>">Última</a>
    <?php endif; ?>
</div>
    </div>
</div>
<script>
    function agregarProducto() {
        const productosList = document.getElementById('productos-list');
        const productoItem = document.createElement('div');
        productoItem.innerHTML = `
            <input type="text" name="productos[]" placeholder="Producto" required>
            <button type="button" onclick="this.parentElement.remove()">Eliminar</button>
        `;
        productosList.appendChild(productoItem);
    }
</script>
</body>
</html>