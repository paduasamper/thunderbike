<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reparaciones - ThunderBike</title>
  <link rel="icon" type="image/png" href="../img/thunderbikes.png">
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
        <li class="nav-item"><a class="nav-link" href="inicio.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="perfil.php">Perfil</a></li>
        <li class="nav-item"><a class="nav-link" href="reparaciones.php">Reparaciones</a></li>
        <li class="nav-item"><a class="nav-link" href="clientes.php">Clientes</a></li>
        <li class="nav-item"><a class="nav-link" href="facturacion.php">Facturación</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2 class="text-center">Reparaciones Realizadas</h2>

  <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addModal">Agregar Reparación</button>

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
                          <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' onclick='loadEditForm(" . $row['id'] . ")'>Editar</button>
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
</div>

<!-- Modal para Agregar Reparación -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Agregar Reparación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="controladores/agregar_reparacion.php" method="post">
        <div class="modal-body">
          <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente:</label>
            <select name="cliente_id" class="form-select" required>
              <option value="" disabled selected>Seleccionar...</option>
              <?php
                $clientes = $mysqli->query("SELECT id, nombre FROM clientes");
                while ($cliente = $clientes->fetch_assoc()) {
                  echo "<option value='{$cliente['id']}'>" . htmlspecialchars($cliente['nombre']) . "</option>";
                }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="producto_id" class="form-label">Producto:</label>
            <select name="producto_id" class="form-select" required>
              <option value="" disabled selected>Seleccionar...</option>
              <?php
                $productos = $mysqli->query("SELECT id, nombre FROM productos");
                while ($producto = $productos->fetch_assoc()) {
                  echo "<option value='{$producto['id']}'>" . htmlspecialchars($producto['nombre']) . "</option>";
                }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="usuario_id" class="form-label">Mecánico:</label>
            <select name="usuario_id" class="form-select" required>
              <option value="" disabled selected>Seleccionar...</option>
              <?php
                $mecanicos = $mysqli->query("SELECT id, nombre FROM usuarios WHERE rol = 'mecanico'");
                while ($mecanico = $mecanicos->fetch_assoc()) {
                  echo "<option value='{$mecanico['id']}'>" . htmlspecialchars($mecanico['nombre']) . "</option>";
                }
              ?>
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
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success">Agregar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para Editar -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Editar Reparación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editForm" action="controladores/editar_reparacion.php" method="post">
        <div class="modal-body">
          <input type="hidden" name="id" id="editId">
          <div class="mb-3">
            <label for="cliente" class="form-label">Cliente:</label>
            <input type="text" class="form-control" id="editCliente" name="cliente" required>
          </div>
          <div class="mb-3">
            <label for="producto" class="form-label">Producto:</label>
            <input type="text" class="form-control" id="editProducto" name="producto" required>
          </div>
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea class="form-control" id="editDescripcion" name="descripcion" required></textarea>
          </div>
          <div class="mb-3">
            <label for="fecha" class="form-label">Fecha:</label>
            <input type="date" class="form-control" id="editFecha" name="fecha" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function loadEditForm(id) {
  // Realiza una solicitud AJAX para obtener los datos de la reparación
  fetch(`controladores/obtener_reparacion.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        alert(data.error);
      } else {
        // Rellena los campos del formulario
        document.getElementById('editId').value = data.id;
        document.getElementById('editCliente').value = data.cliente;
        document.getElementById('editProducto').value = data.producto;
        document.getElementById('editDescripcion').value = data.descripcion;
        document.getElementById('editFecha').value = data.fecha_reparacion;
      }
    })
    .catch(error => {
      console.error('Error al cargar la reparación:', error);
      alert('Ocurrió un error al cargar los datos.');
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
