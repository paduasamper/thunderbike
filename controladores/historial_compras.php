<?php
include "conexions.php"; // Asegúrate de que este archivo exista y esté configurado correctamente

// Obtener el ID del cliente desde la URL
$clientId = isset($_GET['client_id']) ? intval($_GET['client_id']) : 0;

$error = ''; // Variable para almacenar errores

if ($clientId > 0) {
    // Consulta para obtener los datos del cliente
    $stmtClient = $pdo->prepare('SELECT * FROM clientes WHERE id = ?');
    $stmtClient->execute([$clientId]);
    $client = $stmtClient->fetch();

    if ($client) {
        // Consulta para obtener el historial de compras del cliente
        $stmtPurchases = $pdo->prepare('SELECT * FROM ventas WHERE cliente_id = ?');
        $stmtPurchases->execute([$clientId]);
        $purchases = $stmtPurchases->fetchAll();
    } else {
        $error = "Cliente no encontrado.";
    }
} else {
    $error = "ID de cliente no válido.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras</title>
    <link rel="icon" type="image/png" href="img/thunderbikes.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
            <a href="../clientes.php" class="button">Volver a Clientes</a>
        <?php else: ?>
            <h1>Historial de Compras de <?php echo htmlspecialchars($client['nombre']); ?></h1>
            <a href="../clientes.php" class="button">Volver a Clientes</a>
            <?php if ($purchases): ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Descripción</th>
                    </tr>
                    <?php foreach ($purchases as $purchase): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($purchase['producto_vendido_id']); ?></td>
                            <td><?php echo htmlspecialchars($purchase['fecha_venta']); ?></td>
                            <td><?php echo htmlspecialchars($purchase['total']); ?></td>
                            <td><?php echo htmlspecialchars($purchase['descripcion_venta']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No hay compras registradas para este cliente.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
