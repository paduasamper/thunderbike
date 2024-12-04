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
    <!-- Logo y título apilados verticalmente -->
    <div class="d-flex flex-column align-items-start me-auto">
      <img src="img/thunderbikes.png" alt="ThunderBike" style="width: 50px; height: 50px; margin-bottom: 5px;">
      <h1 class="text-white mb-0" style="font-size: 1.5rem;">THUNDERBIKE</h1>
    </div>

    <!-- Botones de navegación alineados a la derecha -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="inicio.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="perfil.php">Perfil</a></li>
        <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>
        <li class="nav-item"><a class="nav-link" href="clientes.php">Clientes</a></li>
        <li class="nav-item"><a class="nav-link" href="insumos.php">Insumos</a></li>
        <li class="nav-item"><a class="nav-link" href="proveedores.php">Proveedores</a></li>
        <li class="nav-item"><a class="nav-link" href="reparaciones.php">Reparaciones</a></li>
        <li class="nav-item"><a class="nav-link" href="facturacion.php">Facturación</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2 class="text-center">Reparaciones Realizadas</h2>

  <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addModal">Agregar Reparación</button>
  

  <!-- Formulario de búsqueda -->
  <form method="GET" class="row g-3 search-form">
    <div class="col-md-4">
      <input type="text" name="mecanico" class="form-control" placeholder="Buscar por mecánico" value="<?= htmlspecialchars($_GET['mecanico'] ?? '') ?>">
    </div>
    <div class="col-md-4">
      <input type="text" name="cliente" class="form-control" placeholder="Buscar por cliente" value="<?= htmlspecialchars($_GET['cliente'] ?? '') ?>">
    </div>
    <div class="col-md-4">
      <input type="date" name="fecha" class="form-control" value="<?= htmlspecialchars($_GET['fecha'] ?? '') ?>">
    </div>
    <div class="col-md-12 text-center mt-2">
      <button type="submit" class="btn btn-primary">Buscar</button>
      <a href="reparaciones.php" class="btn btn-secondary">Limpiar</a>
    </div>
  </form>

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

          $whereClauses = [];
          if (!empty($_GET['mecanico'])) {
              $mecanico = $mysqli->real_escape_string($_GET['mecanico']);
              $whereClauses[] = "u.nombre LIKE '%$mecanico%'";
          }
          if (!empty($_GET['cliente'])) {
              $cliente = $mysqli->real_escape_string($_GET['cliente']);
              $whereClauses[] = "c.nombre LIKE '%$cliente%'";
          }
          if (!empty($_GET['fecha'])) {
              $fecha = $mysqli->real_escape_string($_GET['fecha']);
              $whereClauses[] = "r.fecha_reparacion = '$fecha'";
          }
          
          // Construir la cláusula WHERE dinámica
          $where = count($whereClauses) > 0 ? "WHERE " . implode(" AND ", $whereClauses) : "";
                    // Configuración de paginación
                    $records_per_page = 4;
                    $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                    $offset = ($current_page - 1) * $records_per_page;
          
                    // Contar registros totales
                    $count_sql = "SELECT COUNT(*) as total FROM reparaciones r
                                  JOIN clientes c ON r.cliente_id = c.id
                                  JOIN productos p ON r.producto_id = p.id
                                  JOIN usuarios u ON r.usuario_id = u.id
                                  $where";
                    $count_result = $mysqli->query($count_sql);
                    $total_records = $count_result->fetch_assoc()['total'];
                    $total_pages = ceil($total_records / $records_per_page);
          // Consulta con filtros
          $sql = "
              SELECT r.id, c.nombre AS cliente_nombre, p.nombre AS producto_nombre, r.descripcion, r.fecha_reparacion, u.nombre AS nombre_mecanico
              FROM reparaciones r
              JOIN clientes c ON r.cliente_id = c.id
              JOIN productos p ON r.producto_id = p.id
              JOIN usuarios u ON r.usuario_id = u.id
              $where
              ORDER BY r.fecha_reparacion DESC
              LIMIT $records_per_page OFFSET $offset
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
    <!-- Paginación -->
    <nav>
    <ul class="pagination">
      <?php if ($current_page > 1): ?>
        <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $current_page - 1])) ?>">Anterior</a></li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
          <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>

      <?php if ($current_page < $total_pages): ?>
        <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $current_page + 1])) ?>">Siguiente</a></li>
      <?php endif; ?>
    </ul>
  </nav>
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
      console.log(data);
      if (data.error) {
        alert(data.error);
    } else {
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
