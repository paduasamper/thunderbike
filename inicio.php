<?php
// Datos de conexión a la base de datos
$host = '127.0.0.1';
$dbname = 'thunderbike';
$username = 'root';  // Ajusta el usuario según tu configuración
$password = '';      // Ajusta la contraseña según tu configuración

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consultas a la base de datos
    $sqlFacturacionMesActual = "SELECT SUM(total) AS total_facturacion_mes_actual FROM facturacion WHERE MONTH(fecha) = MONTH(CURRENT_DATE) AND YEAR(fecha) = YEAR(CURRENT_DATE)";
    $stmtFacturacionMesActual = $pdo->prepare($sqlFacturacionMesActual);
    $stmtFacturacionMesActual->execute();
    $facturacionMesActual = $stmtFacturacionMesActual->fetch(PDO::FETCH_ASSOC);

    // Si no hay datos, asignar un valor predeterminado
    $facturacionMesActual = $facturacionMesActual ?? ['total_facturacion_mes_actual' => 0];

    // Comparativo con mes anterior
    $sqlFacturacionMesAnterior = "SELECT SUM(total) AS total_facturacion_mes_anterior FROM facturacion WHERE MONTH(fecha) = MONTH(CURRENT_DATE) - 1 AND YEAR(fecha) = YEAR(CURRENT_DATE)";
    $stmtFacturacionMesAnterior = $pdo->prepare($sqlFacturacionMesAnterior);
    $stmtFacturacionMesAnterior->execute();
    $facturacionMesAnterior = $stmtFacturacionMesAnterior->fetch(PDO::FETCH_ASSOC);
    $facturacionMesAnterior = $facturacionMesAnterior ?? ['total_facturacion_mes_anterior' => 0];

    // Top facturadores
    $sqlTopFacturadores = "SELECT u.nombre, SUM(f.total) AS total_facturacion FROM facturacion f JOIN usuarios u ON f.vendedor_id = u.id GROUP BY f.vendedor_id ORDER BY total_facturacion DESC LIMIT 5";
    $stmtTopFacturadores = $pdo->prepare($sqlTopFacturadores);
    $stmtTopFacturadores->execute();
    $topFacturadores = $stmtTopFacturadores->fetchAll(PDO::FETCH_ASSOC);

    // Top mecánicos
    $sqlTopMecanicos = "SELECT u.nombre, COUNT(r.id) AS total_reparaciones FROM reparaciones r JOIN usuarios u ON r.mecanico_id = u.id GROUP BY r.mecanico_id ORDER BY total_reparaciones DESC LIMIT 5";
    $stmtTopMecanicos = $pdo->prepare($sqlTopMecanicos);
    $stmtTopMecanicos->execute();
    $topMecanicos = $stmtTopMecanicos->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Dashboard - ThunderBike</title>
    <link rel="icon" type="image/png" href="../img/thunderbikes.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .card-body {
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .list-group-item {
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <h1 class="text-center mb-5">Dashboard de ThunderBike</h1>

        <div class="row mb-4">
            <!-- Facturación del Mes Actual -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Facturación del Mes Actual</div>
                    <div class="card-body">
                        <p class="h4">$<?= number_format($facturacionMesActual['total_facturacion_mes_actual'], 2) ?></p>
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
        <div class="row mb-4">
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
        </div>

        <!-- Gráficos -->
        <div class="row mb-4">
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
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderColor: ['#218838', '#c82333'],
                    borderWidth: 2
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
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    fill: true
                }]
            }
        });
    </script>
</body>
</html>
