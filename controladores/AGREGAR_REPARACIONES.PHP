<?php
// Configuración de la conexión a la base de datos
$host = "localhost";
$dbname = "thunderbike";
$username = "root";
$password = "";

// Crear conexión
$mysqli = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $cliente_id = isset($_POST['cliente_id']) ? intval($_POST['cliente_id']) : null;
    $producto_id = isset($_POST['producto_id']) ? intval($_POST['producto_id']) : null;
    $usuario_id = isset($_POST['usuario_id']) ? intval($_POST['usuario_id']) : null;
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;
    $fecha_reparacion = isset($_POST['fecha_reparacion']) ? $_POST['fecha_reparacion'] : null;

    // Validar que los campos requeridos no estén vacíos
    if ($cliente_id && $producto_id && $usuario_id && $descripcion && $fecha_reparacion) {
        // Preparar la consulta para insertar la reparación
        $stmt = $mysqli->prepare("INSERT INTO reparaciones (cliente_id, producto_id, usuario_id, descripcion, fecha_reparacion) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $cliente_id, $producto_id, $usuario_id, $descripcion, $fecha_reparacion);

        // Ejecutar la consulta y verificar el resultado
        if ($stmt->execute()) {
            // Redirigir con mensaje de éxito
            header("Location: ../reparaciones.php?success=1");
        } else {
            // Mostrar error si algo falla
            echo "Error al agregar la reparación: " . $stmt->error;
        }

        // Cerrar la consulta preparada
        $stmt->close();
    } else {
        echo "Todos los campos son obligatorios.";
    }
}

// Cerrar la conexión
$mysqli->close();
?>
