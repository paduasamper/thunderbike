<?php
// Configuración de la conexión a la base de datos para proveedores

$servername = "localhost"; // Nombre del servidor MySQL
$username = "root"; // Nombre de usuario de MySQL
$password = ""; // Contraseña de MySQL
$dbname = "thunderbike"; // Nombre de la base de datos

// Crear conexión
$sql_proveedores = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($sql_proveedores->connect_error) {
    die("Conexión fallida: " . $sql_proveedores->connect_error);
}
?>
