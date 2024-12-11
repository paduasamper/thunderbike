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

    // Consulta para obtener todos los registros de la tabla 'insumos'
    $sql = "SELECT * FROM insumos";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Obtener los resultados como un array asociativo
    $insumos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Insumos</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #808080; /* Gris */
        }

        h1 {
            text-align: center;
            color: white; /* Color blanco para el título */
            margin-top: 20px;
        }

        /* Barra de navegación */
        nav {
            background-color: #343a40;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav .logo-titulo {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        nav img {
            width: 50px;
            height: 50px;
        }

        nav h1 {
            font-size: 1.5rem;
            margin: 5px 0 0 0;
            color: white; /* Título en blanco */
        }

        .navbar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .navbar-nav li {
            margin-left: 15px;
        }

        .navbar-nav a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            padding: 5px 10px;
            transition: background-color 0.3s;
        }

        .navbar-nav a:hover {
            background-color: #495057;
            border-radius: 5px;
        }

        /* Estilos de la tabla */
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white; /* Fondo blanco de la tabla */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #d4af37; /* Dorado */
            color: white;
        }

        td img {
            max-width: 80px;
            height: auto;
        }

        tbody tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        tbody tr:hover {
            background-color: #e9ecef;
        }

        /* Responsividad */
        @media (max-width: 768px) {
            nav h1 {
                font-size: 1.2rem;
            }

            .navbar-nav {
                flex-direction: column;
                align-items: flex-end;
            }

            .navbar-nav li {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
<nav>
    <!-- Contenedor izquierdo: Logo sobre el título -->
    <div class="logo-titulo">
        <img src="img/thunderbikes.png" alt="ThunderBike">
        <h1>THUNDERBIKE</h1>
    </div>
    <!-- Contenedor derecho: Navegación -->
    <ul class="navbar-nav">
        <li><a href="inicio.php">Inicio</a></li>
        <li><a href="perfil.php">Perfil</a></li>
        <li><a href="usuarios.php">Usuarios</a></li>
        <li><a href="clientes.php">Clientes</a></li>
        <li><a href="proveedores.php">Proveedores</a></li>
        <li><a href="reparaciones.php">Reparaciones</a></li>
        <li><a href="facturacion.php">Facturación</a></li>
    </ul>
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
                            <img src="uploads/<?php echo htmlspecialchars($insumo['imagen']); ?>" alt="Imagen del insumo">
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
</body>
</html>


