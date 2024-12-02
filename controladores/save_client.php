<?php
// Conexión a la base de datos
include "conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['clientId']; // ID del cliente
    $documento = $_POST['numero_identificacion']; // Documento
    $nombre = $_POST['nombre']; // Nombre
    $direccion = $_POST['direccion']; // Dirección
    $telefono = $_POST['telefono']; // Teléfono
    $correo = $_POST['correo']; // Correo electrónico

    // Si el ID está vacío, es un nuevo cliente
    if (empty($id)) {
        // Insertar nuevo cliente
        $stmt = $pdo->prepare("INSERT INTO clientes (numero_identificacion, nombre, direccion, telefono, correo) 
                            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$documento, $nombre, $direccion, $telefono, $correo]);
    } else {
        // Actualizar cliente existente
        $stmt = $pdo->prepare("UPDATE clientes SET numero_identificacion = ?, nombre = ?, direccion = ?, telefono = ?, correo = ? 
                            WHERE id = ?");
        $stmt->execute([$documento, $nombre, $direccion, $telefono, $correo, $id]);
    }

    // Redirigir o mostrar un mensaje
    header("Location: clientes.php");
}
?>
