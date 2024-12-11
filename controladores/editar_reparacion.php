<?php
$mysqli = new mysqli("localhost", "root", "", "thunderbike");

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

if (!isset($_POST['id'])) {
    die("ID no especificado");
}

$id = intval($_POST['id']);
$descripcion = $mysqli->real_escape_string($_POST['descripcion']);
$fecha = $mysqli->real_escape_string($_POST['fecha']);

// Actualiza la reparación
$sql = "UPDATE reparaciones 
        SET descripcion = '$descripcion', fecha_reparacion = '$fecha' 
        WHERE id = $id";

if ($mysqli->query($sql)) {
    header("Location: ../reparaciones.php?mensaje=editado");
} else {
    die("Error al actualizar: " . $mysqli->error);
}
?>
