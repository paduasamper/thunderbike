<?php
include "controladores/conexion.php"; // Ajusta según tu estructura

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $estado = isset($_POST['estado']) ? intval($_POST['estado']) : 0;

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE clientes SET estado = :estado WHERE id = :id");
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            http_response_code(200);
            echo "Estado actualizado correctamente.";
        } else {
            http_response_code(500);
            echo "Error al actualizar el estado.";
        }
    } else {
        http_response_code(400);
        echo "Datos inválidos.";
    }
} else {
    http_response_code(405);
    echo "Método no permitido.";
}
?>
