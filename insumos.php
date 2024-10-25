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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        img {
            max-width: 100px; /* Limitar el tamaño de la imagen */
            height: auto;
        }
    </style>
</head>
<body>

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
