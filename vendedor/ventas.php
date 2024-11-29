<?php
ob_start(); // Iniciar el almacenamiento en búfer de salida

$dsn = 'mysql:host=localhost;dbname=thunderbike;charset=utf8';
$username = 'root';
$password = '';

try {
    // Crear una nueva conexión PDO
    $pdo = new PDO($dsn, $username, $password);
    // Configurar el modo de error de PDO para que lance excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

// Obtener opciones para el menú desplegable de productos
$sql_productos = "SELECT id, nombre, precio FROM productos";
$stmt_productos = $pdo->query($sql_productos);
$result_productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

// Obtener opciones para el menú desplegable de usuarios (vendedores)
$sql_vendedores = "SELECT id, nombre FROM usuarios WHERE rol = 'vendedor'";
$stmt_vendedores = $pdo->query($sql_vendedores);
$result_vendedores = $stmt_vendedores->fetchAll(PDO::FETCH_ASSOC);

// Inicializar la variable $result_ventas
$result_ventas = [];

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_cliente = $_POST["nombre_cliente"];
    $direccion_cliente = $_POST["direccion_cliente"];
    $telefono_cliente = $_POST["telefono_cliente"];
    $producto_vendido_id = $_POST["producto_vendido_id"];
    $descripcion_venta = $_POST["descripcion_venta"];
    $total = $_POST["total"];
    $fecha_venta = $_POST["fecha_venta"];
    $usuario_id = $_POST["usuario_id"];

    // Verificar si el cliente ya existe
    $sql_check_cliente = "SELECT id FROM clientes WHERE nombre = :nombre_cliente";
    $stmt_check_cliente = $pdo->prepare($sql_check_cliente);
    $stmt_check_cliente->bindParam(':nombre_cliente', $nombre_cliente, PDO::PARAM_STR);
    $stmt_check_cliente->execute();
    $cliente = $stmt_check_cliente->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        $cliente_id = $cliente['id'];
    } else {
        // Insertar nuevo cliente y obtener el ID
        $sql_insert_cliente = "INSERT INTO clientes (nombre, direccion, telefono) VALUES (:nombre_cliente, :direccion_cliente, :telefono_cliente)";
        $stmt_insert_cliente = $pdo->prepare($sql_insert_cliente);
        $stmt_insert_cliente->bindParam(':nombre_cliente', $nombre_cliente, PDO::PARAM_STR);
        $stmt_insert_cliente->bindParam(':direccion_cliente', $direccion_cliente, PDO::PARAM_STR);
        $stmt_insert_cliente->bindParam(':telefono_cliente', $telefono_cliente, PDO::PARAM_STR);
        $stmt_insert_cliente->execute();
        $cliente_id = $pdo->lastInsertId(); // Obtener el ID del nuevo cliente
    }

    // Consultas preparadas para evitar inyecciones SQL
    $sql_insert = "INSERT INTO ventas (cliente_id, producto_vendido_id, descripcion_venta, total, fecha_venta, usuario_id) 
    VALUES (:cliente_id, :producto_vendido_id, :descripcion_venta, :total, :fecha_venta, :usuario_id)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
    $stmt_insert->bindParam(':producto_vendido_id', $producto_vendido_id, PDO::PARAM_INT);
    $stmt_insert->bindParam(':descripcion_venta', $descripcion_venta, PDO::PARAM_STR);
    $stmt_insert->bindParam(':total', $total, PDO::PARAM_STR);
    $stmt_insert->bindParam(':fecha_venta', $fecha_venta, PDO::PARAM_STR);
    $stmt_insert->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

    if ($stmt_insert->execute()) {
        // Redirigir a la misma página para evitar el reenvío del formulario
        header("Location: " . $_SERVER['PHP_SELF']);
        exit(); // Importante: salir del script después de la redirección
    } else {
        echo "Error: No se pudo agregar la venta.";
    }
}

// Mostrar las ventas realizadas en la tabla
$sql_ventas = "SELECT v.id, c.nombre AS nombre_cliente, c.direccion, c.telefono, p.nombre AS nombre_producto, v.descripcion_venta, v.total, v.fecha_venta, u.nombre AS nombre_vendedor
                FROM ventas v
                JOIN clientes c ON v.cliente_id = c.id
                JOIN productos p ON v.producto_vendido_id = p.id
                JOIN usuarios u ON v.usuario_id = u.id";
$stmt_ventas = $pdo->query($sql_ventas);
$result_ventas = $stmt_ventas->fetchAll(PDO::FETCH_ASSOC);

$pdo = null; // Cerrar la conexión después de procesar la consulta

ob_end_flush(); // Liberar el almacenamiento en búfer de salida y enviar el contenido al navegador
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas Realizadas</title>
    <style>
    /* Estilos CSS generales */
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      overflow-y: scroll;
      position: relative;
    }
    .navtop {
        background-color: rgba(0, 0, 0, 0.5);
        padding: 10px;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .navtop a {
        color: black;
        text-decoration: none;
        margin: 0 10px;
    }

    .navtop a:hover {
        color: goldenrod;
    }
    .container {
      max-width: 800px;
      margin: auto;
      overflow: hidden;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
      position: relative;
      z-index: 2;
    }
    h2 {
      margin-bottom: 10px;
    }
    form {
      margin-bottom: 10px;
      display: none;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    select, input[type="text"], input[type="date"], textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 5px;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-sizing: border-box;
    }
    textarea {
      height: 100px;
      resize: vertical;
    }
    .btn {
      background-color: #e0e0e0;
      color: black;
      padding: 8px 17px;
      border-radius: 1px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      margin: 5px 0;
      text-align: center;
    }
    .btn.edit {
      background-color: #333;
    }
    .btn.delete {
      background-color: #333;
    }
    .button-container {
      margin-top: 20px;
    }
    .button-container button {
      background-color: #333;
      color: #fff;
      padding: 5px 15px;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
    }
    .button-container button:hover {
      background-color: #555;
    }
    #background-video {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 1;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .show-form {
      display: block;
      margin-top: 20px;
    }
  </style>
</head>
<body>
    <nav class="navtop">
        <div>
            <div class="container">
                <div class="button-container">
                    <a href="vendedor_dashboard.php" id="indexBtn" class="button">Inicio</a>
                    <a href="perfil.php" id="perfilBtn" class="button">Perfil</a>
                    <a href="client.php" id="clientesBtn" class="button">Clientes</a>
                    <a href="productos.php" id="productosBtn" class="button">Productos</a>
                    <a href="ventas.php" id="ventasBtn" class="button">Ventas</a>
                    <a href="facturacion.php" id="facturacionBtn" class="button">Facturacion</a>
                </div>
            </div>
        </div>
    </nav>

    <video id="background-video" autoplay muted loop>
        <source src="..\img/ventas.mp4" type="video/mp4">
    </video>
    <br>
    <div class="container">
        <h2>Ventas Realizadas</h2>
        <div class="scrollable-table">
            <table>
            <thead>
                <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Producto Vendido</th>
                <th>Descripción</th>
                <th>Total</th>
                <th>Fecha de Venta</th>
                <th>Vendedor</th>
                </tr>
            </thead>
            <tbody>
        <?php
        // Mostrar las ventas realizadas en la tabla
        if (is_array($result_ventas) && count($result_ventas) > 0) {
            foreach ($result_ventas as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["nombre_cliente"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["direccion"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["telefono"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["nombre_producto"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["descripcion_venta"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["total"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["fecha_venta"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["nombre_vendedor"]) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No se encontraron ventas.</td></tr>";
        }
        ?>
                </tbody>
            </table>
        </div>
        <br>
        <a href="#" id="showFormBtn" class="btn">Registrar Venta</a>
        <form id="ventaForm" method="post">
            <label for="nombre_cliente">Nombre del Cliente:</label>
            <input type="text" name="nombre_cliente" id="nombre_cliente" required>
            <br>
            <label for="direccion_cliente">Dirección:</label>
            <input type="text" name="direccion_cliente" id="direccion_cliente">
            <br>
            <label for="telefono_cliente">Telefono:</label>
            <input type="text" name="telefono_cliente" id="telefono_cliente">
            <br>
            <label for="producto_vendido_id">Producto:</label>
            <select name="producto_vendido_id" id="producto_vendido_id" required>
              <?php
              if (count($result_productos) > 0) {
                foreach ($result_productos as $row) {
                  echo "<option value='" . htmlspecialchars($row["id"]) . "' data-precio='" . htmlspecialchars($row["precio"]) . "'>" . htmlspecialchars($row["nombre"]) . "</option>";
                }
              }
              ?>
            </select>
            <br>
            <label for="descripcion_venta">Descripción:</label>
            <textarea name="descripcion_venta" id="descripcion_venta" required></textarea>
            <br>
            <label for="total">Total:</label>
            <input type="number" name="total" id="total" step="0.01" required>
            <br>
            <label for="fecha_venta">Fecha de Venta:</label>
            <input type="date" name="fecha_venta" id="fecha_venta" required>
            <br>
            <label for="usuario_id">Vendedor:</label>
            <select name="usuario_id" id="usuario_id" required>
                <?php
                if (count($result_vendedores) > 0) {
                    foreach ($result_vendedores as $row) {
                        echo "<option value='" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["nombre"]) . "</option>";
                    }
                }
                ?>
            </select>
            <br><br>
            <input type="submit" value="Agregar Venta" class="btn">
        </form>

    </div>

    <script>
        document.getElementById('showFormBtn').addEventListener('click', function(event) {
            event.preventDefault();
            var form = document.getElementById('ventaForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        });
    </script>
    <script>
    // Seleccionar el campo de producto y el campo de total
    const productoSelect = document.getElementById('producto_vendido_id');
    const totalInput = document.getElementById('total');

    // Evento cuando se selecciona un producto
    productoSelect.addEventListener('change', function() {
        // Obtener el precio del producto seleccionado
        const selectedOption = productoSelect.options[productoSelect.selectedIndex];
        const precio = selectedOption.getAttribute('data-precio');
        
        // Asignar el precio al campo de total
        totalInput.value = precio;
    });
</script>

</body>
</html>