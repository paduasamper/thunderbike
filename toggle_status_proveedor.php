<?php
include "controladores/conexion.php"; // Asegúrate de incluir la conexión a la base de datos

// Verificar si los parámetros necesarios están presentes
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = ($_GET['status'] === 'on') ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE proveedores SET activo = :status WHERE id = :id");
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Estado actualizado."]);
    } else {
        echo json_encode(["success" => false, "message" => "No se pudo actualizar el estado."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Parámetros faltantes."]);
}
?>
