<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reparaciones - ThunderBike</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
    }
    .container {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .table-container {
      margin-top: 20px;
    }
    .btn {
      margin-right: 5px;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">ThunderBike</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
      <a href="inicio.php">Inicio</a>
            <a href="perfil.php">Perfil</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="clientes.php">Clientes</a>
            <a href="insumos.php">Insumos</a>
            <a href="proveedores.php">Proveedores</a>
            <a href="facturacion.php">Facturacion</a>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2 class="text-center">Reparaciones Realizadas</h2>

  <div class="table-container">
    <table class="table table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Producto</th>
          <th>Descripción</th>
          <th>Fecha</th>
          <th>Mecánico</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $mysqli = new mysqli("localhost", "root", "", "thunderbike");

          if ($mysqli->connect_error) {
              die("Error de conexión: " . $mysqli->connect_error);
          }

          $sql = "
            SELECT r.id, c.nombre AS cliente_nombre, p.nombre AS producto_nombre, r.descripcion, r.fecha_reparacion, u.nombre AS nombre_mecanico
            FROM reparaciones r
            JOIN clientes c ON r.cliente_id = c.id
            JOIN productos p ON r.producto_id = p.id
            JOIN usuarios u ON r.usuario_id = u.id
            ORDER BY r.fecha_reparacion DESC
          ";
          $result = $mysqli->query($sql);

          if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["cliente_nombre"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["producto_nombre"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["descripcion"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["fecha_reparacion"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["nombre_mecanico"]) . "</td>";
                  echo "<td>
                          <a href='controladores/editar_reparacion.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Editar</a>
                          <a href='controladores/eliminar_reparacion.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Eliminar esta reparación?\");'>Eliminar</a>
                        </td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='7' class='text-center'>No se encontraron reparaciones</td></tr>";
          }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Botón para mostrar el formulario -->
  <button class="btn btn-primary mt-3" onclick="toggleForm()">Agregar Reparación</button>

  <!-- Formulario oculto inicialmente -->
  <form id="reparacionForm" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" style="display:none;" class="mt-4">
    <div class="mb-3">
      <label for="cliente_id" class="form-label">Cliente:</label>
      <select name="cliente_id" class="form-select" required>
        <option value="" disabled selected>Seleccionar...</option>
        <?php while ($row = $result_clientes->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3">
      <label for="producto_id" class="form-label">Producto:</label>
      <select name="producto_id" class="form-select" required>
        <option value="" disabled selected>Seleccionar...</option>
        <?php while ($row = $result_productos->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3">
      <label for="usuario_id" class="form-label">Mecánico:</label>
      <select name="usuario_id" class="form-select" required>
        <option value="" disabled selected>Seleccionar...</option>
        <?php while ($row = $result_mecanicos->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3">
      <label for="descripcion" class="form-label">Descripción:</label>
      <textarea name="descripcion" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
      <label for="fecha_reparacion" class="form-label">Fecha de Reparación:</label>
      <input type="date" name="fecha_reparacion" class="form-control" value="<?= date('Y-m-d') ?>" required>
    </div>
    <button type="submit" class="btn btn-success">Registrar Reparación</button>
  </form>
</div>

<script>
  function toggleForm() {
    const form = document.getElementById('reparacionForm');
    form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
  }
</script>
</body>
</html>