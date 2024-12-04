<?php
$mysqli = new mysqli("localhost", "root", "", "thunderbike");

if ($mysqli->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $mysqli->connect_error]));
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "
        SELECT r.id, c.nombre AS cliente, p.nombre AS producto, r.descripcion, r.fecha_reparacion
        FROM reparaciones r
        JOIN clientes c ON r.cliente_id = c.id
        JOIN productos p ON r.producto_id = p.id
        WHERE r.id = $id
    ";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "No se encontró la reparación."]);
    }
} else {
    echo json_encode(["error" => "ID no proporcionado."]);
}
?>
