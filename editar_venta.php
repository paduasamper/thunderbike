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

// Obtener el ID de la venta a editar
$id = $_GET['id'] ?? 0;

// Obtener datos de la venta
$sql = "SELECT v.id, c.nombre AS nombre_cliente, c.direccion, c.telefono, p.nombre AS nombre_producto, v.descripcion_venta, v.total, v.fecha_venta, u.nombre AS nombre_vendedor
        FROM ventas v
        JOIN clientes c ON v.cliente_id = c.id
        JOIN productos p ON v.producto_vendido_id = p.id
        JOIN usuarios u ON v.usuario_id = u.id
        WHERE v.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$venta = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener opciones para el menú desplegable de productos
$sql_productos = "SELECT id, nombre FROM productos";
$stmt_productos = $pdo->query($sql_productos);
$result_productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

// Obtener opciones para el menú desplegable de usuarios (vendedores)
$sql_vendedores = "SELECT id, nombre FROM usuarios WHERE rol = 'vendedor'";
$stmt_vendedores = $pdo->query($sql_vendedores);
$result_vendedores = $stmt_vendedores->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_cliente = $_POST["nombre_cliente"];
    $direccion_cliente = $_POST["direccion_cliente"];
    $telefono_cliente = $_POST["telefono_cliente"];
    $producto_vendido_id = $_POST["producto_vendido_id"];
    $descripcion_venta = $_POST["descripcion_venta"];
    $total = $_POST["total"];
    $fecha_venta = $_POST["fecha_venta"];
    $usuario_id = $_POST["usuario_id"];

    // Actualizar la venta
    $sql_update = "UPDATE ventas SET producto_vendido_id = :producto_vendido_id, descripcion_venta = :descripcion_venta, total = :total, fecha_venta = :fecha_venta, usuario_id = :usuario_id
                    WHERE id = :id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(':producto_vendido_id', $producto_vendido_id, PDO::PARAM_INT);
    $stmt_update->bindParam(':descripcion_venta', $descripcion_venta, PDO::PARAM_STR);
    $stmt_update->bindParam(':total', $total, PDO::PARAM_STR);
    $stmt_update->bindParam(':fecha_venta', $fecha_venta, PDO::PARAM_STR);
    $stmt_update->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt_update->execute()) {
        header("Location: ventas.php");
        exit();
    } else {
        echo "Error: No se pudo actualizar la venta.";
    }
}

$pdo = null; // Cerrar la conexión después de procesar la consulta
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Venta</title>
    <style>
        body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        }
        .container {
        width: 90%;
        max-width: 800px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        }
    h1 {
    text-align: center;
    color: #333;
    }
    form {
    display: grid;
    gap: 15px;
    }
    label {
    font-weight: bold;
    color: #555;
    }
    select, input[type="text"], textarea, input[type="date"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    }
    textarea {
    resize: vertical;
    height: 100px;
    }
    input[type="submit"] {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    }
    input[type="submit"]:hover {
    background-color: #0056b3;
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Venta</h2>
        <form method="post">
            <br>
            <label for="nombre_cliente">Nombre del Cliente:</label>
            <input type="text" name="nombre_cliente" id="nombre_cliente" value="<?php echo htmlspecialchars($venta['nombre_cliente']); ?>" required>
            
            <label for="direccion_cliente">Dirección:</label>
            <input type="text" name="direccion_cliente" id="direccion_cliente" value="<?php echo htmlspecialchars($venta['direccion']); ?>">
            
            <label for="telefono_cliente">Telefono:</label>
            <input type="text" name="telefono_cliente" id="telefono_cliente" value="<?php echo htmlspecialchars($venta['telefono']); ?>">
            
            <label for="producto_vendido_id">Producto:</label>
            <select name="producto_vendido_id" id="producto_vendido_id" required>
                <?php
                foreach ($result_productos as $row) {
                    $selected = ($venta['nombre_producto'] == $row['nombre']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row["id"]) . "' $selected>" . htmlspecialchars($row["nombre"]) . "</option>";
                }
                ?>
            </select>
            
            <label for="descripcion_venta">Descripción:</label>
            <textarea name="descripcion_venta" id="descripcion_venta" required><?php echo htmlspecialchars($venta['descripcion_venta']); ?></textarea>
            
            <label for="total">Total:</label>
            <input type="number" name="total" id="total" step="0.01" value="<?php echo htmlspecialchars($venta['total']); ?>" required>
            
            <label for="fecha_venta">Fecha de Venta:</label>
            <input type="date" name="fecha_venta" id="fecha_venta" value="<?php echo htmlspecialchars($venta['fecha_venta']); ?>" required>
            
            <label for="usuario_id">Vendedor:</label>
            <select name="usuario_id" id="usuario_id" required>
                <?php
                foreach ($result_vendedores as $row) {
                    $selected = ($venta['nombre_vendedor'] == $row['nombre']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row["id"]) . "' $selected>" . htmlspecialchars($row["nombre"]) . "</option>";
                }
                ?>
            </select>
            <br>
            <input type="submit" value="Actualizar Venta" class="btn">
        </form>
        <div class="button" ></div>
        <a href="ventas.php" class="btn-back">Volver a Ventas</a>
    </div>
</body>
</html>