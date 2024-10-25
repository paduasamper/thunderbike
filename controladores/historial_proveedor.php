<?php
// historial_proveedor.php

header('Content-Type: application/json');

// Configuración de la conexión a la base de datos
$host = 'localhost'; // Cambia esto si tu base de datos no está en el mismo servidor
$db = 'thunderbike'; // Cambia esto al nombre de tu base de datos
$user = 'root'; // Cambia esto al usuario de tu base de datos
$pass = ''; // Cambia esto a la contraseña del usuario

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Conexión fallida: ' . $e->getMessage()]);
    exit;
}

// Obtén el ID del proveedor desde la solicitud
$proveedor_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($proveedor_id <= 0) {
    echo json_encode([]);
    exit;
}

// Prepara la consulta SQL para obtener el historial de reparaciones
$sql = '
    SELECT p.nombre AS proveedor, pr.nombre AS producto, r.descripcion, r.fecha_reparacion
    FROM reparaciones_proveedores rp
    JOIN proveedores p ON rp.proveedor_id = p.id
    JOIN reparaciones r ON rp.reparacion_id = r.id
    JOIN productos pr ON r.producto_id = pr.id
    WHERE p.id = :proveedor_id
    ORDER BY r.fecha_reparacion DESC
';

// Prepara y ejecuta la consulta
$stmt = $pdo->prepare($sql);
$stmt->execute(['proveedor_id' => $proveedor_id]);

// Obtén los resultados
$historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devuelve los resultados en formato JSON
echo json_encode($historial);
