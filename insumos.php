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
        /* Estilo global */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }

        /* Barra de navegación */
        .navtop {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .navtop img {
            width: 40px;
            height: 40px;
        }
        .navtop a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
        .navtop a:hover {
            color: #FFD700;
        }

        /* Contenedor principal */
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .insumo-imagen {
            width: 80px;
            height: auto;
        }

        /* Estilo de paginación */
        .pagination {
            text-align: center;
            margin: 20px 0;
        }
        .pagination a {
            padding: 10px 15px;
            margin: 0 5px;
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

        /* Reglas de diseño responsivo */
        @media (max-width: 768px) {
            .navtop {
                font-size: 14px;
            }
            table {
                display: block;
                overflow-x: auto; /* Hacer tabla desplazable */
            }
            .insumo-imagen {
                width: 60px; /* Reducir tamaño de imagen */
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 18px;
            }
            .navtop a {
                display: block; /* Cambiar enlaces a bloques */
                margin: 5px 0;
            }
            table {
                font-size: 12px; /* Reducir tamaño del texto en tablas */
            }
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navtop">
    <div>
        <img src="img/thunderbikes.png" alt="Thunderbikes" style="width: 50px; height: 50px;">
        <h1>THUNDERBIKE</h1>
    </div>
        <div>
            <a href="inicio.php">Inicio</a>
            <a href="perfil.php">Perfil</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="clientes.php">Clientes</a>
            <a href="insumos.php">Insumos</a>
            <a href="proveedores.php">Proveedores</a>
            <a href="reparaciones.php">Reparaciones</a>
            <a href="facturacion.php">Facturacion</a>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container">
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
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="insumos.php?pagina=<?php echo $i; ?>" class="<?php echo ($i == $paginaActual) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>
