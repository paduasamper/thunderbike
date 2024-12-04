<?php
$mysqli = new mysqli("localhost", "root", "", "thunderbike");

if ($mysqli->connect_error) {
    die(json_encode(['error' => 'Error de conexión con la base de datos']));
}

if (!isset($_GET['id'])) {
    die(json_encode(['error' => 'ID no especificado']));
}

$id = intval($_GET['id']);
$sql = "SELECT r.id, c.nombre AS cliente, p.nombre AS producto, r.descripcion, r.fecha_reparacion 
        FROM reparaciones r
        JOIN clientes c ON r.cliente_id = c.id
        JOIN productos p ON r.producto_id = p.id
        WHERE r.id = $id";
$result = $mysqli->query($sql);

if ($result->num_rows === 0) {
    die(json_encode(['error' => 'Reparación no encontrada']));
}

echo json_encode($result->fetch_assoc());
?>
