<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thunderbike";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM reparaciones WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $reparacion = $result->fetch_assoc();
    } else {
        die("No se encontró la reparación");
    }
} else {
    die("ID no especificado");
}

// Obtener opciones para clientes, productos y mecánicos
$sql_clientes = "SELECT id, nombre FROM clientes";
$result_clientes = $conn->query($sql_clientes);

$sql_productos = "SELECT id, nombre FROM productos";
$result_productos = $conn->query($sql_productos);

$sql_mecanicos = "SELECT id, nombre FROM usuarios WHERE rol = 'mecanico'";
$result_mecanicos = $conn->query($sql_mecanicos);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_id = $_POST["cliente_id"];
    $producto_id = $_POST["producto_id"];
    $descripcion = $_POST["descripcion"];
    $costo = $_POST["costo"];
    $fecha_reparacion = $_POST["fecha_reparacion"];
    $usuario_id = $_POST["usuario_id"];

    $sql_update = "UPDATE reparaciones SET cliente_id='$cliente_id', producto_id='$producto_id', descripcion='$descripcion', costo='$costo', fecha_reparacion='$fecha_reparacion', usuario_id='$usuario_id' WHERE id=$id";
    if ($conn->query($sql_update) === TRUE) {
        header("Location: ../mecanico/reparaciones.php");
        exit();
    } else {
        echo "Error: " . $sql_update . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Reparación</title>
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
  <class="container">
    <h1>Editar Reparación</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>" method="post">
      <label for="cliente_id">Cliente:</label>
      <select name="cliente_id" required>
          <?php while ($row_cliente = $result_clientes->fetch_assoc()) {
              $selected = ($row_cliente['id'] == $reparacion['cliente_id']) ? 'selected' : '';
              echo "<option value='" . $row_cliente['id'] . "' $selected>" . $row_cliente['nombre'] . "</option>";
          } ?>
      </select>

      <label for="producto_id">Producto:</label>
      <select name="producto_id" required>
          <?php while ($row_producto = $result_productos->fetch_assoc()) {
              $selected = ($row_producto['id'] == $reparacion['producto_id']) ? 'selected' : '';
              echo "<option value='" . $row_producto['id'] . "' $selected>" . $row_producto['nombre'] . "</option>";
          } ?>
      </select>

      <label for="usuario_id">Mecánico:</label>
      <select name="usuario_id" required>
          <?php while ($row_mecanico = $result_mecanicos->fetch_assoc()) {
              $selected = ($row_mecanico['id'] == $reparacion['usuario_id']) ? 'selected' : '';
              echo "<option value='" . $row_mecanico['id'] . "' $selected>" . $row_mecanico['nombre'] . "</option>";
          } ?>
      </select>

      <label for="descripcion">Descripción de la Reparación:</label>
      <textarea name="descripcion" required><?php echo $reparacion['descripcion']; ?></textarea>

      <label for="costo">Costo Total:</label>
      <input type="text" name="costo" value="<?php echo $reparacion['costo']; ?>" required>

      <label for="fecha_reparacion">Fecha de Reparación:</label>
      <input type="date" id="fecha_reparacion" name="fecha_reparacion" value="<?php echo $reparacion['fecha_reparacion']; ?>" required>

      <input type="submit" value="Actualizar Reparación">
    </form>
    <div class="button" ></div>
    <a href="../mecanico/reparaciones.php" id="volverarepacionesBtn" class="button">Volver a Reparaciones</a>
        
  </div>
</body>
</html>