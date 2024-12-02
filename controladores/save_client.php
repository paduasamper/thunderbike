<?php
include "conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_GET['action'] ?? '';

    if ($accion === 'add') {
        // Recibir los datos del formulario
        $documento = $_POST['numero_identificacion'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];

        // Validar datos (opcional, pero recomendado)
        if (empty($documento) || empty($nombre) || empty($direccion) || empty($telefono) || empty($correo)) {
            echo "Todos los campos son obligatorios.";
            exit;
        }

        // Insertar en la base de datos
        try {
            $stmt = $pdo->prepare("INSERT INTO clientes (numero_identificacion, nombre, direccion, telefono, correo) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$documento, $nombre, $direccion, $telefono, $correo]);

            echo "Cliente agregado correctamente.";
        } catch (PDOException $e) {
            echo "Error al agregar el cliente: " . $e->getMessage();
        }
    } elseif ($action === 'edit') {
        // Editar un cliente existente
        $stmt = $pdo->prepare('UPDATE clientes SET numero_identificacion=?, nombre = ?, direccion = ?, telefono = ?, correo=? WHERE id = ?');
        $result = $stmt->execute([$numero_identificacion, $nombre, $direccion, $telefono, $clientId, $correo]);
        echo $result ? 'Cliente actualizado exitosamente' : 'Error al actualizar el cliente';
    }
}
?>
