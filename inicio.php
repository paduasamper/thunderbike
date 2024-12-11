<?php
// Datos de conexión a la base de datos
$host = '127.0.0.1';
$dbname = 'thunderbike';
$username = 'root';  // Ajusta el usuario según tu configuración
$password = '';      // Ajusta la contraseña según tu configuración

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Obtener facturación del mes actual
    $sqlFacturacionMesActual = "SELECT SUM(total) AS total_facturacion_mes_actual FROM facturas WHERE MONTH(fecha_factura) = MONTH(CURRENT_DATE) AND YEAR(fecha_factura) = YEAR(CURRENT_DATE)";
    $stmtFacturacionMesActual = $pdo->prepare($sqlFacturacionMesActual);
    $stmtFacturacionMesActual->execute();
    $facturacionMesActual = $stmtFacturacionMesActual->fetch(PDO::FETCH_ASSOC);

    // Comparativo con mes anterior
    $sqlFacturacionMesAnterior = "SELECT SUM(total) AS total_facturacion_mes_anterior FROM facturas WHERE MONTH(fecha_factura) = MONTH(CURRENT_DATE) - 1 AND YEAR(fecha_factura) = YEAR(CURRENT_DATE)";
    $stmtFacturacionMesAnterior = $pdo->prepare($sqlFacturacionMesAnterior);
    $stmtFacturacionMesAnterior->execute();
    $facturacionMesAnterior = $stmtFacturacionMesAnterior->fetch(PDO::FETCH_ASSOC);

    // Top facturadores
    $sqlTopFacturadores = "SELECT u.nombre, SUM(f.total) AS total_facturacion FROM facturas f JOIN usuarios u ON f.vendedor = u.id GROUP BY f.vendedor ORDER BY total_facturacion DESC LIMIT 5";
    $stmtTopFacturadores = $pdo->prepare($sqlTopFacturadores);
    $stmtTopFacturadores->execute();
    $topFacturadores = $stmtTopFacturadores->fetchAll(PDO::FETCH_ASSOC);

    // // Relación insumos y productos
    // $sqlRelacionInsumosProductos = "SELECT p.nombre AS producto, i.nombre AS insumo, SUM(r.cantidad_insumo) AS total_insumo, SUM(f.cantidad) AS total_producto FROM reparaciones r JOIN insumos i ON r.insumo_id = i.id JOIN productos p ON r.producto_id = p.id LEFT JOIN facturas f ON f.productos = p.nombre GROUP BY p.id, i.id";
    // $stmtRelacionInsumosProductos = $pdo->prepare($sqlRelacionInsumosProductos);
    // $stmtRelacionInsumosProductos->execute();
    // $relacionInsumosProductos = $stmtRelacionInsumosProductos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

// Si no se recuperan datos, asignamos un valor predeterminado
$facturacionMesActual = $facturacionMesActual ?? ['total_facturacion_mes_actual' => 0];
$facturacionMesAnterior = $facturacionMesAnterior ?? ['total_facturacion_mes_anterior' => 0];
$topFacturadores = $topFacturadores ?? [];
$topMecanicos = $topMecanicos ?? [];
$relacionInsumosProductos = $relacionInsumosProductos ?? [];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThunderBike</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #212529;
            /* Fondo oscuro */
            color: #f8f9fa;
            /* Texto claro */
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #343a40;
            /* Fondo oscuro */
        }

        .logo-title {
            text-align: right;
        }

        .logo-title img {
            width: 60px;
            height: 60px;
            margin-bottom: 5px;
        }

        .logo-title h1 {
            font-size: 1.5rem;
            margin: 0;
        }

        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin: 0;
        }

        .nav-links a {
            color: #f8f9fa;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-links a:hover {
            color: #ffc107;
            /* Amarillo para hover */
        }

        .content {
            margin: 20px;
        }

        .card {
            background-color: #343a40;
            border: 1px solid #495057;
            color: #f8f9fa;
        }

        .card-header {
            background-color: #495057;
            color: #f8f9fa;
        }

        table {
            background-color: #343a40;
            border: 1px solid #495057;
            color: #f8f9fa;
        }

        table th {
            background-color: #495057;
            color: #f8f9fa;
        }

        table tr:nth-child(even) {
            background-color: #3e444a;
        }

        table tr:hover {
            background-color: #495057;
        }
    </style>
</head>

<body>
    <div class="header-container">
        <!-- Botones a la izquierda -->
        <nav class="nav-links">
            <a href="inicio.php">Inicio</a>
            <a href="perfil.php">Perfil</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="clientes.php">Clientes</a>
            <a href="insumos.php">Insumos</a>
            <a href="proveedores.php">Proveedores</a>
            <a href="reparaciones.php">Reparaciones</a>
            <a href="facturacion.php">Facturación</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>

        <!-- Logo y título a la derecha -->
        <div class="logo-title">
            <img src="img/thunderbikes.png" alt="Thunderbikes">
            <h1>THUNDERBIKE</h1>
        </div>
    </div>

    <div class="row">
        <!-- Facturación del Mes Actual -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Facturación del Mes Actual</div>
                <div class="card-body">
                    <p>Total Facturación: $<?= number_format($facturacionMesActual['total_facturacion_mes_actual'], 2) ?></p>
                </div>
            </div>
        </div>

        <!-- Comparativo de Facturación -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Comparativo de Facturación</div>
                <div class="card-body">
                    <p><strong>Mes Actual vs Mes Anterior:</strong> $<?= number_format($facturacionMesActual['total_facturacion_mes_actual'], 2) ?> vs $<?= number_format($facturacionMesAnterior['total_facturacion_mes_anterior'], 2) ?></p>
                </div>
            </div>
        </div>

        <!-- Top 5 Facturadores -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Top 5 Facturadores</div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($topFacturadores as $facturador): ?>
                            <li class="list-group-item"><?= $facturador['nombre'] ?>: $<?= number_format($facturador['total_facturacion'], 2) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 5 Mecánicos -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Top 5 Mecánicos</div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($topMecanicos as $mecanico): ?>
                            <li class="list-group-item"><?= $mecanico['nombre'] ?>: <?= $mecanico['total_reparaciones'] ?> reparaciones</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Relación de Productos, Insumos y Servicios -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Relación de Insumos y Productos</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Insumo</th>
                                <th>Total Insumo</th>
                                <th>Total Producto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($relacionInsumosProductos as $item): ?>
                                <tr>
                                    <td><?= $item['producto'] ?></td>
                                    <td><?= $item['insumo'] ?></td>
                                    <td><?= $item['total_insumo'] ?></td>
                                    <td><?= $item['total_producto'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mt-4">
        <!-- Gráfico de Comparativo de Facturación -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Comparativo de Facturación: Mes Actual vs Mes Anterior</div>
                <div class="card-body">
                    <canvas id="facturacionComparativoChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Facturación Mensual -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Facturación Mensual</div>
                <div class="card-body">
                    <canvas id="facturacionMensualChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    </div>

    <script>
        // Gráfico de Comparativo de Facturación
        var ctx1 = document.getElementById('facturacionComparativoChart').getContext('2d');
        var facturacionComparativoChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Mes Actual', 'Mes Anterior'],
                datasets: [{
                    label: 'Facturación ($)',
                    data: [<?= $facturacionMesActual['total_facturacion_mes_actual'] ?>, <?= $facturacionMesAnterior['total_facturacion_mes_anterior'] ?>],
                    backgroundColor: ['#4caf50', '#f44336'],
                    borderColor: ['#388e3c', '#d32f2f'],
                    borderWidth: 1
                }]
            }
        });

        // Gráfico de Facturación Mensual
        var ctx2 = document.getElementById('facturacionMensualChart').getContext('2d');
        var facturacionMensualChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                datasets: [{
                    label: 'Facturación Anual ($)',
                    data: [12000, 15000, 13000, 17000, 16000, 18000, 21000, 22000, 24000, 25000, 27000, 28000], // Modificar con datos reales
                    borderColor: '#1976d2',
                    backgroundColor: 'rgba(25, 118, 210, 0.2)',
                    fill: true
                }]
            }
        });
    </script>
</body>

</html>