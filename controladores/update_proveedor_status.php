<?php
include "conexion.php";

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'], $data['status'])) {
    $id = intval($data['id']);
    $status = in_array($data['status'], ['on', 'off']) ? $data['status'] : 'off';

    $stmt = $pdo->prepare("UPDATE proveedores SET status = :status WHERE id = :id");
    $stmt->execute([':status' => $status, ':id' => $id]);

    if ($stmt->rowCount()) {
        echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
}
