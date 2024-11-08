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
    $insumosPorPagina = 5;
    
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

    // Consulta para obtener los registros con paginación
    $sql = "SELECT * FROM insumos LIMIT $insumosPorPagina OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Obtener los resultados como un array asociativo
    $insumos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Lista de Insumos</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .insumo-imagen {
        width: 100px;  /* Ajusta el ancho de la imagen */
        height: auto; /* Mantiene la proporción de la imagen */
    }
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
      max-width: 650px;
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

    textarea {
      height: 100px;
      resize: vertical;
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
            /* Estilos de la paginación */
            .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 16px;
            margin: 0 4px;
            text-decoration: none;
            border: 1px solid #ddd;
            color: #333;
        }
        .pagination a:hover {
            background-color: #ddd;
        }
        .pagination .active {
            background-color: #4CAF50;
            color: white;
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
                    <a href="insumos.php" id="insumosBtn" class="button">Insumos</a>
                    <a href="proveedores.php" id="proveedoresBtn" class="button">Proveedores</a>
                    <a href="ventas.php" id="ventasBtn" class="button">Ventas</a>
                    <a href="reparaciones.php" id="reparacionesBtn" class="button">Reparaciones</a>
            </div>
        </div>
    </div>
</nav>

<h1>Lista de Insumos</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Descripción</th>
                <th>Producto ID</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($insumos) > 0): ?>
                <?php foreach ($insumos as $insumo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($insumo['id']); ?></td>
                        <td><?php echo htmlspecialchars($insumo['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($insumo['cantidad']); ?></td>
                        <td><?php echo htmlspecialchars($insumo['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($insumo['producto_id']); ?></td>
                        <td>
                            <?php if (!empty($insumo['imagen'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($insumo['imagen']); ?>" alt="Imagen del insumo" class="insumo-imagen">
                            <?php else: ?>
                                Sin imagen
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No hay insumos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="pagination">
        <?php
        // Enlaces de paginación
        for ($i = 1; $i <= $totalPaginas; $i++) {
            $activeClass = ($i == $paginaActual) ? 'active' : '';
            echo "<a href='insumos.php?pagina=$i' class='$activeClass'>$i</a>";
        }
        ?>
    </div>
</body>
</html>