<?php
// Datos de conexión a la base de datos
$host = '127.0.0.1';
$dbname = 'thunderbike';
$username = 'root';  // Ajusta el usuario según tu configuración
$password = '';      // Ajusta la contraseña según tu configuración

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener ventas del mes actual
    $sqlVentasMesActual = "SELECT SUM(total) AS total_ventas_mes_actual FROM ventas WHERE MONTH(fecha) = MONTH(CURRENT_DATE) AND YEAR(fecha) = YEAR(CURRENT_DATE)";
    $stmtVentasMesActual = $pdo->prepare($sqlVentasMesActual);
    $stmtVentasMesActual->execute();
    $ventasMesActual = $stmtVentasMesActual->fetch(PDO::FETCH_ASSOC);

    // Comparativo con mes anterior
    $sqlVentasMesAnterior = "SELECT SUM(total) AS total_ventas_mes_anterior FROM ventas WHERE MONTH(fecha) = MONTH(CURRENT_DATE) - 1 AND YEAR(fecha) = YEAR(CURRENT_DATE)";
    $stmtVentasMesAnterior = $pdo->prepare($sqlVentasMesAnterior);
    $stmtVentasMesAnterior->execute();
    $ventasMesAnterior = $stmtVentasMesAnterior->fetch(PDO::FETCH_ASSOC);

    // Top vendedores
    $sqlTopVendedores = "SELECT u.nombre, SUM(v.total) AS total_ventas FROM ventas v JOIN usuarios u ON v.vendedor_id = u.id GROUP BY v.vendedor_id ORDER BY total_ventas DESC LIMIT 5";
    $stmtTopVendedores = $pdo->prepare($sqlTopVendedores);
    $stmtTopVendedores->execute();
    $topVendedores = $stmtTopVendedores->fetchAll(PDO::FETCH_ASSOC);

    // Top reparaciones
    $sqlTopMecanicos = "SELECT u.nombre, COUNT(r.id) AS total_reparaciones FROM reparaciones r JOIN usuarios u ON r.mecanico_id = u.id GROUP BY r.mecanico_id ORDER BY total_reparaciones DESC LIMIT 5";
    $stmtTopMecanicos = $pdo->prepare($sqlTopMecanicos);
    $stmtTopMecanicos->execute();
    $topMecanicos = $stmtTopMecanicos->fetchAll(PDO::FETCH_ASSOC);

    // Relación insumos y productos
    $sqlRelacionInsumosProductos = "SELECT p.nombre AS producto, i.nombre AS insumo, SUM(r.cantidad_insumo) AS total_insumo, SUM(v.cantidad) AS total_producto FROM reparaciones r JOIN insumos i ON r.insumo_id = i.id JOIN productos p ON r.producto_id = p.id LEFT JOIN ventas v ON v.producto_id = p.id GROUP BY p.id, i.id";
    $stmtRelacionInsumosProductos = $pdo->prepare($sqlRelacionInsumosProductos);
    $stmtRelacionInsumosProductos->execute();
    $relacionInsumosProductos = $stmtRelacionInsumosProductos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

// Si no se recuperan datos, asignamos un valor predeterminado
$ventasMesActual = $ventasMesActual ?? ['total_ventas_mes_actual' => 0];
$ventasMesAnterior = $ventasMesAnterior ?? ['total_ventas_mes_anterior' => 0];
$topVendedores = $topVendedores ?? [];
$topMecanicos = $topMecanicos ?? [];
$relacionInsumosProductos = $relacionInsumosProductos ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ThunderBike</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="sales.php">Ventas</a></li>
            <li><a href="reports.php">Reportes</a></li>
            <li><a href="settings.php">Configuraciones</a></li>
        </ul>
    </nav>

    <div class="container my-4">
        <h1>Dashboard de ThunderBike</h1>
        
        <div class="row">
            <!-- Ventas del Mes Actual -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Ventas del Mes Actual</div>
                    <div class="card-body">
                        <p>Total Ventas: $<?= number_format($ventasMesActual['total_ventas_mes_actual'], 2) ?></p>
                    </div>
                </div>
            </div>

            <!-- Comparativo de Ventas -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Comparativo de Ventas</div>
                    <div class="card-body">
                        <p><strong>Mes Actual vs Mes Anterior:</strong> $<?= number_format($ventasMesActual['total_ventas_mes_actual'], 2) ?> vs $<?= number_format($ventasMesAnterior['total_ventas_mes_anterior'], 2) ?></p>
                    </div>
                </div>
            </div>

            <!-- Top 5 Vendedores -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Top 5 Vendedores</div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php foreach ($topVendedores as $vendedor): ?>
                                <li class="list-group-item"><?= $vendedor['nombre'] ?>: $<?= number_format($vendedor['total_ventas'], 2) ?></li>
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
            <!-- Gráfico de Comparativo de Ventas -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Comparativo de Ventas: Mes Actual vs Mes Anterior</div>
                    <div class="card-body">
                        <canvas id="ventasComparativoChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Top 5 Vendedores -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Top 5 Vendedores</div>
                    <div class="card-body">
                        <canvas id="topVendedoresChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
              // Gráfico de Comparativo de Ventas
        var ctx = document.getElementById('ventasComparativoChart').getContext('2d');
        var ventasComparativoChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Mes Actual', 'Mes Anterior'],
                datasets: [{
                    label: 'Ventas ($)',
                    data: [<?= number_format($ventasMesActual['total_ventas_mes_actual'], 2) ?>, <?= number_format($ventasMesAnterior['total_ventas_mes_anterior'], 2) ?>],
                    backgroundColor: ['#4CAF50', '#FF5722'],
                    borderColor: ['#4CAF50', '#FF5722'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de Top 5 Vendedores
        var ctxVendedores = document.getElementById('topVendedoresChart').getContext('2d');
        var topVendedoresChart = new Chart(ctxVendedores, {
            type: 'bar',
            data: {
                labels: [<?php foreach ($topVendedores as $vendedor) { echo '"' . $vendedor['nombre'] . '",'; } ?>],
                datasets: [{
                    label: 'Ventas por Vendedor ($)',
                    data: [<?php foreach ($topVendedores as $vendedor) { echo $vendedor['total_ventas'] . ','; } ?>],
                    backgroundColor: '#FF9800',
                    borderColor: '#FF9800',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>