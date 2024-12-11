<?php
// Datos de conexión a la base de datos
$host = '127.0.0.1';
$dbname = 'thunderbike';
$username = 'root';  // Ajusta el usuario según tu configuración
$password = '';      // Ajusta la contraseña según tu configuración

try {
    // Crear una nueva instancia de PDO para conectarse a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Establecer el modo de error de PDO a excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Número de insumos por página
    $insumosPorPagina = 10;
    
    // Obtener el número total de registros
    $sqlTotal = "SELECT COUNT(*) FROM insumos";
    $stmtTotal = $pdo->prepare($sqlTotal);
    $stmtTotal->execute();
    $totalInsumos = $stmtTotal->fetchColumn();
    
    // Asegurarse de que haya insumos disponibles
    if ($totalInsumos === false) {
        throw new Exception('Error al obtener el total de insumos.');
    }

    // Calcular el total de páginas
    $totalPaginas = ceil($totalInsumos / $insumosPorPagina);

    // Obtener la página actual desde la URL, por defecto es 1
    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    // Asegurarse de que la página actual esté dentro del rango válido
    if ($paginaActual < 1) $paginaActual = 1;
    if ($paginaActual > $totalPaginas) $paginaActual = $totalPaginas;

    // Calcular el offset para la consulta
    $offset = ($paginaActual - 1) * $insumosPorPagina;

    // Procesar filtros
    $filtros = [];
    $parametros = [];
    if (!empty($_GET['buscar'])) {
        $filtros[] = "(nombre LIKE :buscar OR categoria LIKE :buscar)";
        $parametros['buscar'] = '%' . $_GET['buscar'] . '%';
    }
    if (!empty($_GET['categoria'])) {
        $filtros[] = "categoria = :categoria";
        $parametros['categoria'] = $_GET['categoria'];
    }
    if (!empty($_GET['stock'])) {
        $filtros[] = "cantidad <= stock_minimo";
    }

    // Construir consulta con filtros
    $whereClause = !empty($filtros) ? 'WHERE ' . implode(' AND ', $filtros) : '';
    $orderBy = isset($_GET['orden']) ? $_GET['orden'] : 'nombre';
    $direccion = isset($_GET['dir']) ? $_GET['dir'] : 'ASC';

    // Consulta para obtener los registros con paginación
    $sql = "SELECT * FROM insumos $whereClause 
            ORDER BY $orderBy $direccion 
            LIMIT $insumosPorPagina OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($parametros);
    $insumos = $stmt->fetchAll();

    // Obtener total para paginación
    $sqlTotal = "SELECT COUNT(*) FROM insumos $whereClause";
    $stmtTotal = $pdo->prepare($sqlTotal);
    $stmtTotal->execute($parametros);
    $total = $stmtTotal->fetchColumn();
    $totalPaginas = ceil($total / $insumosPorPagina);

    // Obtener categorías para filtro
    $stmtCategorias = $pdo->query("SELECT DISTINCT categoria FROM insumos");
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_COLUMN);

    // Verificar stock bajo
    $stmtStockBajo = $pdo->query("SELECT COUNT(*) FROM insumos WHERE cantidad <= stock_minimo");
    $stockBajo = $stmtStockBajo->fetchColumn();
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Insumos - ThunderBike</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
</head>
<body>
    <!-- Contenido del Navbar incluido directamente -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">ThunderBike</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="inicio.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="perfil.php">Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="usuarios.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clientes.php">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="proveedores.php">Proveedores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reparaciones.php">Reparaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="facturacion.php">Facturación</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestión de Insumos</h1>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoInsumoModal">
                <i class="fas fa-plus"></i> Nuevo Insumo
            </button>
        </div>

        <?php if ($stockBajo = 0): ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Hay <?= $stockBajo ?> insumos con stock bajo
        </div>
        <?php endif; ?>

        <!-- Filtros en el formulario -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar..." value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="categoria" class="form-select">
                            <option value="">Todas las categorías</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['id']) ?>" 
                                    <?= isset($_GET['categoria']) && $_GET['categoria'] == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input type="checkbox" name="stock" class="form-check-input" id="stockBajo" <?= isset($_GET['stock']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="stockBajo">Stock bajo</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="insumos.php" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reiniciar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Mostrar insumos en la tabla -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th><a href="?orden=id">ID</a></th>
                        <th><a href="?orden=nombre">Nombre</a></th>
                        <th><a href="?orden=categoria">Categoría</a></th>
                        <th><a href="?orden=cantidad">Cantidad</a></th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($insumos as $insumo): ?>
                    <tr>
                        <td><?= htmlspecialchars($insumo['id']) ?></td>
                        <td><?= htmlspecialchars($insumo['nombre']) ?></td>
                        <td><?= htmlspecialchars($insumo['categoria']) ?></td>
                        <td><?= htmlspecialchars($insumo['cantidad']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm">Editar</button>
                            <button class="btn btn-danger btn-sm">Eliminar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <nav aria-label="Página de navegación">
            <ul class="pagination">
                <li class="page-item <?= $paginaActual == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=1">Primera</a>
                </li>
                <li class="page-item <?= $paginaActual == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $paginaActual - 1 ?>">Anterior</a>
                </li>
                <li class="page-item <?= $paginaActual == $totalPaginas ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $paginaActual + 1 ?>">Siguiente</a>
                </li>
                <li class="page-item <?= $paginaActual == $totalPaginas ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $totalPaginas ?>">Última</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Modal de nuevo insumo -->
    <div class="modal fade" id="nuevoInsumoModal" tabindex="-1" aria-labelledby="nuevoInsumoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoInsumoModalLabel">Nuevo Insumo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="guardar_insumo.php">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock_minimo" class="form-label">Stock Mínimo</label>
                            <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Insumo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
