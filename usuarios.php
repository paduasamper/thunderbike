<?php
// Conexión a la base de datos
$host = "127.0.0.1";
$dbname = "thunderbike";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = $_POST['accion'];
        $id = $_POST['id'] ?? null;

        if ($accion === 'editar') {
            $nombre = $_POST['nombre'] ?? null;
            $correo = $_POST['correo'] ?? null;
            $rol = $_POST['rol'] ?? null;

            if (!$nombre || !$correo || !$rol) {
                throw new Exception("Todos los campos son obligatorios.");
            }

            editarUsuario($pdo, $id, $nombre, $correo, $rol);
        } elseif ($accion === 'eliminar') {
            eliminarUsuario($pdo, $id);
        } elseif ($accion === 'cambiar_rol') {
            $rol = $_POST['rol'] ?? null;
            cambiarRol($pdo, $id, $rol);
        }
        header("Location: usuarios.php");
        exit;
    }

    $usuarios = listarUsuarios($pdo);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

function listarUsuarios($pdo) {
    try {
        $stmt = $pdo->query("SELECT id, nombre, correo, rol, fecha_creacion FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Error al listar usuarios: " . $e->getMessage());
    }
}

function editarUsuario($pdo, $id, $nombre, $correo, $rol) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo AND id != :id");
    $stmt->execute(['correo' => $correo, 'id' => $id]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("El correo ya está en uso por otro usuario.");
    }

    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = :nombre, correo = :correo, rol = :rol WHERE id = :id");
    $stmt->execute(['nombre' => $nombre, 'correo' => $correo, 'rol' => $rol, 'id' => $id]);
}

function eliminarUsuario($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
}

function cambiarRol($pdo, $id, $rol) {
    $stmt = $pdo->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
    $stmt->execute([$rol, $id]);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Usuarios</title>
    <link rel="stylesheet" href="styles.css"> <!-- Puedes mantener tus estilos aquí -->
    <style>
    /* Estilos globales */
    body {
        font-family: 'Roboto', sans-serif; /* Cambié la tipografía a 'Roboto' */
        margin: 0;
        padding: 0;
        background-color: black;
    }

    h1 {
        text-align: center;
        margin-top: 20px;
    }

    /* Barra de navegación */
    .navtop {
        background-color: #333;
        color: white;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .navtop a {
        color: white;
        text-decoration: none;
        margin: 5px 10px;
    }

    .navtop a:hover {
        color: goldenrod;
    }

    .container {
        margin: 20px auto;
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: 90%;
        max-width: 1200px;
    }

    /* Tabla */
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    th, td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* Botones */
    button, form button {
        padding: 8px 12px;
        background-color: gold;
        color: black;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover, form button:hover {
        background-color: wheat;
    }

    /* Modal */
    #modal-editar {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    #modal-editar .modal-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 400px;
    }

    /* Responsividad */
    @media (max-width: 768px) {
        .navtop {
            flex-direction: column;
            text-align: center;
        }

        table {
            display: block;
            overflow-x: auto;
        }

        th, td {
            white-space: nowrap;
        }

        .container {
            width: 95%;
            padding: 10px;
        }
    }

    @media (max-width: 480px) {
        h1 {
            font-size: 1.5em;
        }

        button, form button {
            padding: 6px 10px;
            font-size: 0.9em;
        }
    }

    /* Estilo para el fondo de video */
    .video-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        overflow: hidden;
    }

    #background-video {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        z-index: -1;
    }

    /* Contenido encima del video */
    .content {
        position: relative;
        z-index: 1;
    }

    /* Ajustes para que el contenido siga siendo legible */
    body {
        background: rgba(0, 0, 0, 0.5); /* Capa de semitransparencia sobre el video */
        color: black;
    }
</style>

</head>
<body>
    <div class="video-background">
        <video autoplay muted loop id="background-video">
            <source src="ruta-del-video.mp4" type="video/mp4">
        </video>
    </div>
    <nav class="navtop">
        <div>
            <img src="img/thunderbikes.png" alt="Thunderbikes" style="width: 50px; height: 50px;">
            <h1>THUNDERBIKE</h1>
        </div>
        <div>
            <a href="inicio.php">Inicio</a>
            <a href="perfil.php">Perfil</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="clientes.php">Clientes</a>
            <a href="insumos.php">Insumos</a>
            <a href="proveedores.php">Proveedores</a>
            <a href="reparaciones.php">Reparaciones</a>
            <a href="facturacion.php">Facturacion</a>
        </div>
    </nav>

    <div class="container">
        <h1>Gestión de Usuarios</h1>
        <table>
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
                            <button onclick="abrirModal('<?= $usuario['id'] ?>', '<?= $usuario['nombre'] ?>', '<?= $usuario['correo'] ?>')">Editar</button>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                <button type="submit" name="accion" value="eliminar" onclick="return confirm('¿Está seguro de eliminar este usuario?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="modal-editar">
        <div class="modal-content">
            <h2>Editar Usuario</h2>
            <form method="post">
                <input type="hidden" name="id" id="editar-id">
                <label for="editar-nombre">Nombre:</label>
                <input type="text" name="nombre" id="editar-nombre" required>
                <br><br>
                <label for="editar-correo">Correo:</label>
                <input type="email" name="correo" id="editar-correo" required>
                <br><br>
                <label for="editar-rol">Rol:</label>
                <select name="rol" id="editar-rol" required>
                    <option value="administrador">Administrador</option>
                    <option value="mecanico">Mecánico</option>
                    <option value="vendedor">Vendedor</option>
                </select>
                <br><br>
                <button type="submit" name="accion" value="editar">Guardar Cambios</button>
                <button type="button" onclick="cerrarModal()">Cancelar</button>
            </form>
        </div>
    </div>

    <script>
        function abrirModal(id, nombre, correo) {
            document.getElementById('editar-id').value = id;
            document.getElementById('editar-nombre').value = nombre;
            document.getElementById('editar-correo').value = correo;
            document.getElementById('modal-editar').style.display = 'flex';
        }

        function cerrarModal() {
            document.getElementById('modal-editar').style.display = 'none';
        }
    </script>
</body>
</html>
