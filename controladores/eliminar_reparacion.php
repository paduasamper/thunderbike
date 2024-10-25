<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thunderbike";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM reparaciones WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: reparaciones.php");
        exit();
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
} else {
    die("ID no especificado");
}

$conn->close();
?>
