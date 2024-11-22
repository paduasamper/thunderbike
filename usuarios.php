<?php
// Conexión a la base de datos
$host = "127.0.0.1";
$dbname = "thunderbike";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Funciones principales

// Listar usuarios
function listarUsuarios($pdo) {
    $stmt = $pdo->query("SELECT * FROM usuarios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Editar usuario
function editarUsuario($pdo, $id, $nombre, $correo, $rol) {
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, correo = ?, rol = ?, fecha_modificacion = NOW() WHERE id = ?");
    return $stmt->execute([$nombre, $correo, $rol, $id]);
}

// Eliminar usuario
function eliminarUsuario($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    return $stmt->execute([$id]);
}

// Cambiar rol del usuario
function cambiarRol($pdo, $id, $rol) {
    $stmt = $pdo->prepare("UPDATE usuarios SET rol = ?, fecha_modificacion = NOW() WHERE id = ?");
    return $stmt->execute([$rol, $id]);
}

// Cambiar contraseña
function cambiarContrasena($pdo, $id, $nuevaClave) {
    $claveHash = password_hash($nuevaClave, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE usuarios SET clave = ?, fecha_modificacion = NOW() WHERE id = ?");
    return $stmt->execute([$claveHash, $id]);
}

// Manejo de acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
    $id = $_POST['id'] ?? null;

    if ($accion === 'editar') {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $rol = $_POST['rol'];
        editarUsuario($pdo, $id, $nombre, $correo, $rol);
    } elseif ($accion === 'eliminar') {
        eliminarUsuario($pdo, $id);
    } elseif ($accion === 'cambiar_rol') {
        $rol = $_POST['rol'];
        cambiarRol($pdo, $id, $rol);
    } elseif ($accion === 'cambiar_contrasena') {
        $nuevaClave = $_POST['nueva_clave'];
        cambiarContrasena($pdo, $id, $nuevaClave);
    }

    header("Location: usuarios.php");
    exit;
}

// Obtener la lista de usuarios para mostrar en la tabla
$usuarios = listarUsuarios($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Usuarios</title>
    <link rel="stylesheet" href="styles.css"> <!-- Agrega estilos según tu preferencia -->
</head>
<body>
<nav class="navtop">
    <div>
        <img src="img/thunderbikes.png" alt="thunderbikes" style="width: 55px; height: 55px;">
        <h1>THUNDERBIKE</h1>
        <div class="container">
            <div class="button-container">
                <a href="perfil.php" id="perfilBtn" class="button">Perfil</a>
                    <a href="usuarios.php" id="UsuariosBtn" class="button">Usuarios</a>
                    <a href="clientes.php" id="clientesBtn" class="button">Clientes</a>
                    <a href="insumos.php" id="insumosBtn" class="button">Insumos</a>
                    <a href="proveedores.php" id="proveedoresBtn" class="button">Proveedores</a>
                    <a href="ventas.php" id="ventasBtn" class="button">Ventas</a>
                    <a href="reparaciones.php" id="reparacionesBtn" class="button">Reparaciones</a>
                <a href="logout.php" id="cerrarSesionBtn" class="button special">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</nav>
        
    <h1>Gestión de Usuarios</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Fecha de Creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id'] ?></td>
                    <td><?= $usuario['nombre'] ?></td>
                    <td><?= $usuario['correo'] ?></td>
                    <td><?= $usuario['rol'] ?></td>
                    <td><?= $usuario['fecha_creacion'] ?></td>
                    <td>
<!-- Formulario para cambiar rol -->
<form method="post" style="display:inline;">
    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
    <select name="rol" required>
        <option value="administrador" <?= $usuario['rol'] === 'administrador' ? 'selected' : '' ?>>Administrador</option>
        <option value="mecanico" <?= $usuario['rol'] === 'mecanico' ? 'selected' : '' ?>>Mecánico</option>
        <option value="vendedor" <?= $usuario['rol'] === 'vendedor' ? 'selected' : '' ?>>Vendedor</option>
    </select>
    <button type="submit" name="accion" value="cambiar_rol">Cambiar Rol</button>
</form>


                        <!-- Formulario para cambiar contraseña -->
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                            <input type="password" name="nueva_clave" placeholder="Nueva contraseña" required>
                            <button type="submit" name="accion" value="cambiar_contrasena">Cambiar Contraseña</button>
                        </form>

                        <!-- Botón para eliminar usuario -->
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                            <button type="submit" name="accion" value="eliminar" onclick="return confirm('¿Está seguro de eliminar este usuario?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
