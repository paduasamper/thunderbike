<?php
ob_start(); // Iniciar el almacenamiento en búfer de salida

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thunderbike";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener opciones para el menú desplegable de clientes
$sql_clientes = "SELECT id, nombre FROM clientes";
$result_clientes = $conn->query($sql_clientes);

// Obtener opciones para el menú desplegable de productos
$sql_productos = "SELECT id, nombre FROM productos";
$result_productos = $conn->query($sql_productos);

// Obtener opciones para el menú desplegable de usuarios (mecánicos)
$sql_mecanicos = "SELECT id, nombre FROM usuarios WHERE rol = 'mecanico'";
$result_mecanicos = $conn->query($sql_mecanicos);

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_id = $_POST["cliente_id"];
    $producto_id = $_POST["producto_id"];
    $descripcion = $_POST["descripcion"];
    $costo = $_POST["costo"];
    $fecha_reparacion = $_POST["fecha_reparacion"];
    $usuario_id = $_POST["usuario_id"];

    // Insertar la reparación en la base de datos
    $sql_reparacion = "INSERT INTO reparaciones (cliente_id, producto_id, descripcion, costo, fecha_reparacion, usuario_id) VALUES ('$cliente_id', '$producto_id', '$descripcion', '$costo', '$fecha_reparacion', '$usuario_id')";
    if ($conn->query($sql_reparacion) === TRUE) {
        // Redirigir a la misma página para evitar el reenvío del formulario
        header("Location: " . $_SERVER['PHP_SELF']);
        exit(); // Importante: salir del script después de la redirección
    } else {
        echo "Error: " . $sql_reparacion . "<br>" . $conn->error;
    }
}

$conn->close();
ob_end_flush(); // Liberar el almacenamiento en búfer de salida y enviar el contenido al navegador
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reparaciones</title>
  <link rel="icon" type="image/png" href="img/thunderbikes.png">
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
        <a href="inicio.php" id="indexBtn" class="button">Inicio</a>
        <a href="perfil.php" id="perfilBtn" class="button">Perfil</a>
        <a href="clientes.php" id="clientesBtn" class="button">Clientes</a>
        <a href="productos.php" id="productosBtn" class="button">Productos</a>
        <a href="proveedores.php" id="proveedoresBtn" class="button">Proveedores</a>
        <a href="ventas.php" id="ventasBtn" class="button">Ventas</a>
        <a href="reparaciones.php" id="reparacionesBtn" class="button">Reparaciones</a>
      </div>
    </div>
  </div>
</nav>
<!-- Video de fondo -->
<video id="background-video" autoplay muted loop>
  <source src="img/reparaciones.mp4" type="video/mp4">
  Tu navegador no admite la etiqueta de video.
</video>
<br>
<div class="container">
  <h2>Reparaciones Realizadas</h2>
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Producto</th>
          <th>Descripción</th>
          <th>Costo</th>
          <th>Fecha de Reparación</th>
          <th>Nombre Mecánico</th>
        </tr>
      </thead>
      <tbody>
      <?php
        // Conexión a la base de datos
        $mysqli = new mysqli("localhost", "root", "", "thunderbike");

        // Verificar la conexión
        if ($mysqli->connect_error) {
            die("Error de conexión: " . $mysqli->connect_error);
        }

        // Consulta SQL para obtener las reparaciones realizadas con nombres de clientes y productos
        $sql = "
          SELECT r.id, c.nombre AS cliente_nombre, p.nombre AS producto_nombre, r.descripcion, r.costo, r.fecha_reparacion, u.nombre AS nombre_mecanico
          FROM reparaciones r
          JOIN clientes c ON r.cliente_id = c.id
          JOIN productos p ON r.producto_id = p.id
          JOIN usuarios u ON r.usuario_id = u.id
        ";
        $result = $mysqli->query($sql);

        // Mostrar las reparaciones realizadas en la tabla
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["cliente_nombre"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["producto_nombre"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["descripcion"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["costo"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["fecha_reparacion"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["nombre_mecanico"]) . "</td>";
                echo "<td>";
                echo "<a href='controladores/editar_reparacion.php?id=" . htmlspecialchars($row['id']) . "' class='btn'>Editar</a> ";
                echo "<a href='controladores/eliminar_reparacion.php?id=" . htmlspecialchars($row['id']) . "' class='btn' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta reparación?\");'>Eliminar</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No se encontraron reparaciones realizadas</td></tr>";
        }
      ?>
      </tbody>
    </table>
  </div>

  <!-- Botón para mostrar el formulario -->
  <button class="btn show-form" onclick="toggleForm()">Agregar Reparación</button>

  <!-- Formulario de registro oculto inicialmente -->
  <form id="reparacionForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="cliente_id">Cliente:</label>
    <select name="cliente_id" required>
        <option value="" disabled selected>Seleccionar...</option>
        <?php
        while ($row_cliente = $result_clientes->fetch_assoc()) {
            echo "<option value='" . $row_cliente['id'] . "'>" . $row_cliente['nombre'] . "</option>";
        }
        ?>
    </select>

    <label for="producto_id">Producto:</label>
    <select name="producto_id" required>
        <option value="" disabled selected>Seleccionar...</option>
        <?php
        while ($row_producto = $result_productos->fetch_assoc()) {
            echo "<option value='" . $row_producto['id'] . "'>" . $row_producto['nombre'] . "</option>";
        }
        ?>
    </select>

    <label for="usuario_id">Mecánico:</label>
    <select name="usuario_id" required>
        <option value="" disabled selected>Seleccionar...</option>
        <?php
        while ($row_mecanico = $result_mecanicos->fetch_assoc()) {
            echo "<option value='" . $row_mecanico['id'] . "'>" . $row_mecanico['nombre'] . "</option>";
        }
        ?>
    </select>

    <label for="descripcion">Descripción de la Reparación:</label>
    <textarea name="descripcion" required></textarea>

    <label for="costo">Costo Total:</label>
    <input type="text" name="costo" required>

    <label for="fecha_reparacion">Fecha de Reparación:</label>
    <input type="date" id="fecha_reparacion" name="fecha_reparacion" required>

    <input type="submit" value="Registrar Reparación" class="btn">
  </form>

  <script>
    // Mostrar/ocultar el formulario
    function toggleForm() {
      var form = document.getElementById("reparacionForm");
      if (form.style.display === "none" || form.style.display === "") {
        form.style.display = "block";
      } else {
        form.style.display = "none";
      }
    }

    var fechaActual = new Date().toISOString().slice(0, 10);
    document.getElementById("fecha_reparacion").value = fechaActual;
  </script>
</body>
</html>
