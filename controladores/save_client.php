<?php
include 'conexion.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId = $_POST['clientId'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    if ($action === 'add') {
        // Agregar un nuevo cliente
        $stmt = $pdo->prepare('INSERT INTO clientes (nombre, direccion, telefono) VALUES (?, ?, ?)');
        $result = $stmt->execute([$nombre, $direccion, $telefono]);
        echo $result ? 'Cliente agregado exitosamente' : 'Error al agregar el cliente';
    } elseif ($action === 'edit') {
        // Editar un cliente existente
        $stmt = $pdo->prepare('UPDATE clientes SET nombre = ?, direccion = ?, telefono = ? WHERE id = ?');
        $result = $stmt->execute([$nombre, $direccion, $telefono, $clientId]);
        echo $result ? 'Cliente actualizado exitosamente' : 'Error al actualizar el cliente';
    } elseif ($action === 'delete') {
        // Eliminar un cliente
        $stmt = $pdo->prepare('DELETE FROM clientes WHERE id = ?');
        $result = $stmt->execute([$clientId]);
        echo $result ? 'Cliente eliminado exitosamente' : 'Error al eliminar el cliente';
    }
}
?>