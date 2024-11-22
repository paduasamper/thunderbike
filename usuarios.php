<?php
// Conexión a la base de datos
$host = "127.0.0.1";
$dbname = "thunderbike";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
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
    $error = $e->getMessage();
}

function listarUsuarios($pdo) {
    $stmt = $pdo->query("SELECT id, nombre, correo, rol, fecha_creacion FROM usuarios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="styles.css"> <!-- Agrega estilos según tu preferencia -->
    <style>
        /* Estilos del modal */
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
            width: 400px;
        }
        body {
            font-family: Arial, sans-serif; /* Establece la fuente del cuerpo del documento */
            margin: 0; /* Elimina el margen predeterminado del navegador */
            padding: 0; /* Elimina el relleno predeterminado del navegador */
            overflow: hidden; /* Oculta las barras de desplazamiento */
            background-size: cover; /* Asegura que la imagen de fondo cubra toda la pantalla */
            background-repeat: no-repeat; /* Evita que la imagen de fondo se repita */
            background-attachment: fixed; /* Fija la imagen de fondo */
            display: flex; /* Usa flexbox para centrar el contenido */
            justify-content: center; /* Centra el contenido horizontalmente */
            align-items: center; /* Centra el contenido verticalmente */
            height: 100vh; /* Altura del 100% de la ventana gráfica */
        }

        #background-video {
            position: fixed; /* Fija el video en la pantalla */
            top: 50%;
            left: 50%;
            min-width: 100%; /* Abarca toda la pantalla horizontalmente */
            min-height: 100%; /* Abarca toda la pantalla verticalmente */
            width: auto; /* Ajusta el ancho automáticamente */
            height: auto; /* Ajusta la altura automáticamente */
            transform: translate(-50%, -50%); /* Centra el video */
            z-index: -1; /* Coloca el video detrás de otros elementos */
        }

        /* Estilos para el encabezado */
        .navtop {
            background-color: rgba(0, 0, 0, 0.5); /* Fondo semi-transparente negro */
            padding: 10px; /* Relleno de 10px */
            text-align: center; /* Alinea el texto al centro */
        }

        .navtop a {
            color: black; /* Color del enlace */
            text-decoration: none; /* Sin subrayado en los enlaces */
            margin: 0 10px; /* Margen de 10px a cada lado */
        }

        .navtop a:hover {
            color: goldenrod; /* Color del enlace al pasar el ratón */
        }

        .navtop {
            background-color: rgba(0, 0, 0, 0.8); /* Fondo semi-transparente más oscuro */
            padding: 30px 0; /* Relleno de 10px arriba y abajo */
            width: 100%; /* Ancho del 100% */
            position: fixed; /* Fija la posición en la parte superior */
            top: 0; /* Posición en la parte superior */
            left: 0; /* Posición en la parte izquierda */
            z-index: 999; /* Coloca el encabezado por encima de otros elementos */
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8); /* Fondo blanco semi-transparente */
            padding: 35px; /* Relleno de 20px */
            margin: relative; /* Centra horizontalmente */
            width: 70%; /* Ancho del 60% */
            max-width: 750px; /* Ancho máximo de 750px */
            border-radius: 5px; /* Bordes redondeados */
            position: relative; /* Posición relativa */
        }

        table {
            border-collapse: collapse; /* Colapsa los bordes de la tabla */
            width: 100%; /* Ancho del 100% */
            margin-bottom: 20px; /* Margen inferior de 20px */
        }

        th, td {
            border: 1px solid #ddd; /* Bordes de las celdas */
            padding: 8px; /* Relleno de 8px */
            text-align: left; /* Alinea el texto a la izquierda */
        }

        th {
            background-color: #f2f2f2; /* Fondo de las celdas de los encabezados */
        }

        tr:nth-child(even) {
            background-color: #f2f2f2; /* Fondo de las filas pares */
        }

        .button-container {
            position: absolute; /* Posición absoluta */
            top: 20px; /* Posición superior de 20px */
            right: 20px; /* Posición derecha de 20px */
        }

        .button:hover {
            color: goldenrod; /* Color al pasar el ratón */
        }

        .button.active {
            background-color: #45a049; /* Fondo del botón activo */
        }

        #formContainer {
            background-color: #f9f9f9; /* Fondo del formulario */
            padding: 20px; /* Relleno de 20px */
            border-radius: 10px; /* Bordes redondeados */
            position: fixed; /* Posición fija */
            top: 50%; /* Posición superior del 50% */
            left: 50%; /* Posición izquierda del 50% */
            transform: translate(-50%, -50%); /* Centra el formulario */
            display: none; /* Oculta el formulario por defecto */
        }

        #formTitle {
            margin-top: 0; /* Sin margen superior */
        }

        #clientForm input {
            width: calc(100% - 22px); /* Ancho del 100% menos 22px */
            padding: 10px; /* Relleno de 10px */
            margin-top: 10px; /* Margen superior de 10px */
            border: 1px solid #ccc; /* Borde gris claro */
            border-radius: 5px; /* Bordes redondeados */
            box-sizing: border-box; /* Incluye el relleno y el borde en el ancho total */
        }

        #clientForm button {
            background-color: #4CAF50; /* Fondo verde */
            border: none; /* Sin borde */
            color: white; /* Texto blanco */
            padding: 10px 20px; /* Relleno de 10px arriba y abajo, 20px a los lados */
            text-align: center; /* Alinea el texto al centro */
            text-decoration: none; /* Sin subrayado */
            display: inline-block; /* Elemento en línea */
            font-size: 16px; /* Tamaño de fuente de 16px */
            margin-top: 10px; /* Margen superior de 10px */
            border-radius: 5px; /* Bordes redondeados */
            cursor: pointer; /* Cursor de mano */
            transition: background-color 0.3s ease; /* Transición suave del fondo */
        }

        #clientForm button:hover {
            background-color: #45a049; /* Fondo al pasar el ratón */
        }

        .home-button-container {
            position: absolute; /* Posición absoluta */
            top: 20px; /* Posición superior de 20px */
            right: 20px; /* Posición derecha de 20px */
        }
    </style>
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
                    <button onclick="abrirModal('<?= $usuario['id'] ?>', '<?= $usuario['nombre'] ?>', '<?= $usuario['correo'] ?>', '<?= $usuario['rol'] ?>')">Editar</button>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                        <button type="submit" name="accion" value="eliminar" onclick="return confirm('¿Está seguro de eliminar este usuario?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal para editar usuario -->
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
