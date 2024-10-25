<?php
header('Content-Type: application/json');

// Conectar a la base de datos
$mysqli = new mysqli("localhost", "root", "", "thunderbike");

// Verificar conexión
if ($mysqli->connect_error) {
    die(json_encode(['error' => 'Error de conexión: ' . $mysqli->connect_error]));
}

// Obtener los datos enviados
$data = json_decode(file_get_contents('php://input'), true);

$cliente_id = $data['cliente_id'];
$producto_id = $data['producto_id'];
$descripcion = $data['descripcion'];
$costo = $data['costo'];
$fecha_reparacion = $data['fecha_reparacion'];
$mecanico_id = $data['mecanico_id'];

// Preparar la consulta
$sql = "INSERT INTO reparaciones (cliente_id, producto_id, descripcion, costo, fecha_reparacion, mecanico_id) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iisssi", $cliente_id, $producto_id, $descripcion, $costo, $fecha_reparacion, $mecanico_id);

// Ejecutar la consulta
if ($stmt->execute()) {
    // Obtener el ID de la nueva reparación
    $id = $stmt->insert_id;
    echo json_encode(['id' => $id, 'message' => 'Reparación guardada correctamente']);
} else {
    echo json_encode(['error' => 'Error al guardar la reparación: ' . $stmt->error]);
}

// Cerrar la conexión
$stmt->close();
$mysqli->close();
?>

