<?php
include 'conexion.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $proveedorId = $_POST['proveedorId'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    if ($action === 'add') {
        // Agregar un nuevo proveedor
        $stmt = $pdo->prepare('INSERT INTO proveedores (nombre, direccion, telefono) VALUES (?, ?, ?)');
        $result = $stmt->execute([$nombre, $direccion, $telefono]);
        echo $result ? 'Proveedor agregado exitosamente' : 'Error al agregar el proveedor';
    } elseif ($action === 'edit') {
        // Editar un proveedor existente
        $stmt = $pdo->prepare('UPDATE proveedores SET nombre = ?, direccion = ?, telefono = ? WHERE id = ?');
        $result = $stmt->execute([$nombre, $direccion, $telefono, $proveedorId]);
        echo $result ? 'Proveedor actualizado exitosamente' : 'Error al actualizar el proveedor';
    } elseif ($action === 'delete') {
        // Eliminar un proveedor
        $stmt = $pdo->prepare('DELETE FROM proveedores WHERE id = ?');
        $result = $stmt->execute([$proveedorId]);
        echo $result ? 'Proveedor eliminado exitosamente' : 'Error al eliminar el proveedor';
    }
}
?>


