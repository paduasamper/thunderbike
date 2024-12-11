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
        $accion = $_POST['accion'] ?? null;
        $id = $_POST['id'] ?? null;

        if ($accion === 'cambiar_estado') {
            $estado = $_POST['activo'] ?? null;

            // Validamos que los datos necesarios estén presentes
            if ($id === null || $estado === null) {
                throw new Exception("ID o estado faltante.");
            }

            // Cambiar el estado del usuario
            cambiarEstadoUsuario($pdo, $id, $estado);

            // Redireccionar después de cambiar el estado
            header("Location: usuarios.php");
            exit;
        } elseif ($accion === 'editar') {
            $nombre = $_POST['nombre'] ?? null;
            $documento = $_POST['documento'] ?? null;
            $telefono = $_POST['telefono'] ?? null;
            $direccion = $_POST['direccion'] ?? null;
            $correo = $_POST['correo'] ?? null;
            $rol = $_POST['rol'] ?? null;

            if (!$nombre || !$documento || !$telefono || !$direccion || !$correo || !$rol) {
                throw new Exception("Todos los campos son obligatorios.");
            }

            editarUsuario($pdo, $id, $nombre, $documento, $telefono, $direccion, $correo, $rol);
        } elseif ($accion === 'agregar') {
            $nombre = $_POST['nombre'] ?? null;
            $documento = $_POST['documento'] ?? null;
            $telefono = $_POST['telefono'] ?? null;
            $direccion = $_POST['direccion'] ?? null;
            $correo = $_POST['correo'] ?? null;
            $rol = $_POST['rol'] ?? null;

            if (!$nombre || !$documento || !$telefono || !$direccion || !$correo || !$rol) {
                throw new Exception("Todos los campos son obligatorios.");
            }

            agregarUsuario($pdo, $nombre, $documento, $telefono, $direccion, $correo, $rol);
        }

        // Redireccionar después de cualquier acción
        header("Location: usuarios.php");
        exit;
    }

    $usuarios = listarUsuarios($pdo);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// Función para listar usuarios con filtro de búsqueda
function listarUsuarios($pdo) {
    try {
        $nombre = $_GET['nombre'] ?? '';
        $documento = $_GET['documento'] ?? '';

        $sql = "SELECT id, nombre, documento, telefono, direccion, correo, rol, fecha_creacion, activo FROM usuarios WHERE 1=1";
        
        // Agregar condiciones de búsqueda si los campos no están vacíos
        if ($nombre) {
            $sql .= " AND nombre LIKE :nombre";
        }
        if ($documento) {
            $sql .= " AND documento LIKE :documento";
        }

        $stmt = $pdo->prepare($sql);

        // Vincular los parámetros de búsqueda
        if ($nombre) {
            $stmt->bindValue(':nombre', "%$nombre%", PDO::PARAM_STR);
        }
        if ($documento) {
            $stmt->bindValue(':documento', "%$documento%", PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Error al listar usuarios: " . $e->getMessage());
    }
}

// Función para cambiar el estado del usuario
function cambiarEstadoUsuario($pdo, $id, $estado) {
    $stmt = $pdo->prepare("UPDATE usuarios SET activo = :activo WHERE id = :id");
    $stmt->execute(['activo' => $estado, 'id' => $id]);
}

// Función para editar un usuario
function editarUsuario($pdo, $id, $nombre, $documento, $telefono, $direccion, $correo, $rol) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo AND id != :id");
    $stmt->execute(['correo' => $correo, 'id' => $id]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("El correo ya está en uso por otro usuario.");
    }

    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = :nombre, documento = :documento, telefono = :telefono, direccion = :direccion, correo = :correo, rol = :rol WHERE id = :id");
    $stmt->execute([
        'nombre' => $nombre,
        'documento' => $documento,
        'telefono' => $telefono,
        'direccion' => $direccion,
        'correo' => $correo,
        'rol' => $rol,
        'id' => $id
    ]);
}

// Función para agregar un usuario
function agregarUsuario($pdo, $nombre, $documento, $telefono, $direccion, $correo, $rol) {
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, documento, telefono, direccion, correo, rol, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$nombre, $documento, $telefono, $direccion, $correo, $rol]);
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
        background-color: #808080; /* Gris */
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
            /* Estilos para el formulario */
            #formContainer {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            width: 400px;
            max-width: 100%;
        }

        #formTitle {
            margin-bottom: 20px;
            font-size: 20px;
            color: #333;
            text-align: center;
        }

        label {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        input[type="text"]:focus {
            border-color: #5d8f36;
            outline: none;
        }

        button[type="submit"],
        button[type="button"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            font-size: 16px;
            background-color: gold;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover,
        button[type="button"]:hover {
            background-color: goldenrod;
        }
        /* Modal Agregar Usuario */
#modal-agregar {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    width: 400px;
    max-width: 100%;
}

/* Modal Editar Usuario */
#modal-editar {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    width: 400px;
    max-width: 100%;
}
button[type="submit"] {
    padding: 10px 20px; /* Ajustar el relleno para un tamaño adecuado */
    min-width: 80px; /* Establecer un ancho mínimo para los botones */
    text-align: center; /* Asegurar que el texto esté centrado */
    font-size: 16px; /* Tamaño de letra */
    background-color: gold;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button[type="submit"][style] {
    min-width: 80px; /* Ajuste similar para los botones con estilo inline */
    padding: 10px 20px;
    font-size: 16px;
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
            <a href="clientes.php">Clientes</a>
            <a href="insumos.php">Insumos</a>
            <a href="proveedores.php">Proveedores</a>
            <a href="reparaciones.php">Reparaciones</a>
            <a href="facturacion.php">Facturacion</a>
        </div>
    </nav>

    <div class="container">
        <h1>Gestión de Usuarios</h1>
        <!-- Botón Agregar Usuario -->
        <button onclick="abrirModalAgregar()">Agregar Usuario</button>
        <form method="get" action="usuarios.php">
            <input type="text" name="nombre" placeholder="Buscar por nombre" value="<?= $_GET['nombre'] ?? '' ?>" />
            <input type="text" name="documento" placeholder="Buscar por documento" value="<?= $_GET['documento'] ?? '' ?>" />
            <button type="submit">Buscar</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Telefono</th>
                    <th>Dirreción</th>
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
                        <td><?= $usuario['documento'] ?></td>
                        <td><?= $usuario['telefono'] ?></td>
                        <td><?= $usuario['direccion'] ?></td>
                        <td><?= $usuario['correo'] ?></td>
                        <td><?= $usuario['rol'] ?></td>
                        <td><?= $usuario['fecha_creacion'] ?></td>
                        <td>
                            <button onclick="abrirModal('<?= $usuario['id'] ?>', '<?= $usuario['nombre'] ?>', '<?= $usuario['correo'] ?>')">Editar</button>
                            <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                            <input type="hidden" name="accion" value="cambiar_estado">
                            <input type="hidden" name="activo" value="<?= $usuario['activo'] ? 0 : 1 ?>">
                            <button type="submit" style="background-color: <?= $usuario['activo'] ? 'green' : 'red'; ?>; color: white; min-width: 80px; padding: 10px 20px; font-size: 16px;">
                                <?= $usuario['activo'] ? 'ON' : 'OFF' ?>
                            </button>
                        </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
        <!-- Modal Agregar Usuario -->
        <div id="modal-agregar">
        <div class="modal-content">
            <h2>Agregar Usuario</h2>
            <form method="post">
                <input type="hidden" name="id" id="agregar-id">
                <label for="agregar-nombre">Nombre:</label>
                <input type="text" name="nombre" id="agregar-nombre" required>
                <label for="agregar-documento">Documento:</label>
                <input type="text" name="documento" id="agregar-documento" required>
                <label for="agregar-telefono">Teléfono:</label>
                <input type="text" name="telefono" id="agregar-telefono" pattern="\d*" required>
                <label for="agregar-direccion">Dirección:</label>
                <input type="text" name="direccion" id="agregar-direccion" required>
                <label for="agregar-correo">Correo:</label>
                <input type="email" name="correo" id="agregar-correo" required>
                <label for="agregar-rol">Rol:</label>
                <select name="rol" id="editar-rol" required>
                <option value="administrador">Administrador</option>
                <option value="mecanico">Mecánico</option>
                <option value="vendedor">Vendedor</option>
            </select>
                <button type="submit" name="accion" value="agregar">Agregar</button>
                <button type="button" onclick="cerrarModal()">Cancelar</button>
            </form>
        </div>
    </div>
<!-- Modal Editar Usuario -->
<div id="modal-editar">
    <div class="modal-content">
        <h2>Editar Usuario</h2>
        <form method="post">
            <input type="hidden" name="id" id="editar-id" value="<?= isset($usuario['id']) ? $usuario['id'] : '' ?>">
            
            <label for="editar-nombre">Nombre:</label>
            <input type="text" name="nombre" id="editar-nombre" value="<?= isset($usuario['nombre']) ? $usuario['nombre'] : '' ?>" readonly required>
            
            <label for="editar-documento">Documento:</label>
            <input type="text" name="documento" id="editar-documento" value="<?= isset($usuario['documento']) ? $usuario['documento'] : '' ?>" readonly required>
            
            <label for="editar-telefono">Teléfono:</label>
            <input type="text" name="telefono" id="editar-telefono" value="<?= isset($usuario['telefono']) ? $usuario['telefono'] : '' ?>" pattern="\d*" required>
            
            <label for="editar-direccion">Dirección:</label>
            <input type="text" name="direccion" id="editar-direccion" value="<?= isset($usuario['direccion']) ? $usuario['direccion'] : '' ?>" required>
            
            <label for="editar-correo">Correo:</label>
            <input type="email" name="correo" id="editar-correo" value="<?= isset($usuario['correo']) ? $usuario['correo'] : '' ?>" required>
            
            <label for="editar-rol">Rol:</label>
            <select name="rol" id="editar-rol" required>
                <option value="administrador">Administrador</option>
                <option value="mecanico">Mecánico</option>
                <option value="vendedor">Vendedor</option>
            </select>
            <button type="submit" name="accion" value="editar">Guardar Cambios</button>
            <button type="button" onclick="cerrarModal()">Cancelar</button>
        </form>
    </div>
</div>

    <script>
// Función para abrir el modal de agregar usuario
function abrirModalAgregar() {
    document.getElementById('modal-agregar').style.display = 'flex';
}

// Función para abrir el modal de editar usuario
function abrirModal(id, nombre, correo) {
    document.getElementById('editar-id').value = id;
    document.getElementById('editar-nombre').value = nombre;
    document.getElementById('editar-correo').value = correo;
    document.getElementById('modal-editar').style.display = 'flex';
}

// Función para cerrar ambos modales
// Función para cerrar el modal
function cerrarModal() {
    // Se establece el estilo display a 'none' para ocultar el modal
    document.getElementById('modal-agregar').style.display = 'none';
    document.getElementById('modal-editar').style.display = 'none';
}


    </script>
</body>
</html>
