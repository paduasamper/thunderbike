<?php
include "conexion.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['status'])) {
    $id = $data['id'];
    $status = $data['status'] === 'on' ? 1 : 0;

    try {
        $stmt = $pdo->prepare('UPDATE proveedores SET activo = :status WHERE id = :id');
        $stmt->execute([':status' => $status, ':id' => $id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se encontró el proveedor o no hubo cambios.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos recibidos.']);
}
?>
