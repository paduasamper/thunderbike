<?php
include "conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? null;
    $id = $_POST['proveedorId'] ?? null;
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $nit = $_POST['nit'];
    $correo = $_POST['correo'];
    $empresa = $_POST['empresa'];

    try {
        if ($action === 'add') {
            $sql = "INSERT INTO proveedores (nombre, direccion, telefono, nit) VALUES (:nombre, :direccion, :telefono, :nit)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':direccion' => $direccion,
                ':telefono' => $telefono,
                ':nit' => $nit,
                ':correo' => $correo,
                ':empresa' => $empresa,
            ]);
        } elseif ($action === 'edit' && $id) {
            $sql = "UPDATE proveedores SET nombre = :nombre, direccion = :direccion, telefono = :telefono WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':direccion' => $direccion,
                ':telefono' => $telefono,
                ':id' => $id,
            ]);
        }
        header('Location: ../proveedores.php');
    } catch (PDOException $e) {
        die('Error: ' . $e->getMessage());
    }
} else {
    die('Método no permitido.');
}
