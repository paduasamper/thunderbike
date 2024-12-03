<?php
include "conexion.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$id = $data['id'];
$status = $data['status'];

try {
    $stmt = $pdo->prepare('UPDATE proveedores SET status = :status WHERE id = :id');
    $stmt->execute(['status' => $status, 'id' => $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Estado actualizado con éxito.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró el proveedor o no se pudo actualizar.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
