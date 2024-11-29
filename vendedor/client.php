<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Define el juego de caracteres del documento como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configura la vista para dispositivos móviles -->
    <title>Clientes</title> <!-- Título de la página -->
    <link rel="icon" type="image/png" href="..\img/thunderbikes.png"> <!-- Icono de la página -->
    <style>
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
        .pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px; /* Añade un margen superior */
    clear: both; /* Asegura que no haya elementos flotantes interfiriendo */
    position: relative;
}

.pagination a {
    color: black;
    padding: 8px 16px;
    text-decoration: none;
    border: 1px solid #ddd;
    margin: 0 5px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.pagination a.active {
    background-color: goldenrod;
    color: white;
    border: 1px solid goldenrod;
}

.pagination a:hover:not(.active) {
    background-color: #ddd;
}
    </style>
</head>
<body>
<nav class="navtop">
    <div>
        <div class="container">
            <div class="button-container" style="display: flex; justify-content: space-between; align-items: center;">
                <img src="..\img/thunderbikes.png" alt="thunderbikes" style="width: 50px; height: 50px; margin-left: 0;">
                <div class="nav-buttons" style="margin-left: 20px; display: flex; align-items: center;">
                    <a href="vendedor_dashboard.php" id="Vendedor_dashboardBtn" class="button">Inicio</a>
                    <a href="perfil.php" id="perfilBtn" class="button">Perfil</a>
                    <a href="client.php" id="clientesBtn" class="button">Clientes</a>
                    <a href="productos.php" id="ProductosBtn" class="button">Productos</a>
                    <a href="ventas.php" id="ventasBtn" class="button">Ventas</a>
                    <a href="facturacion.php" id="FactuaracionBtn" class="button">Facturacion</a>
                </div>
            </div>
        </div>
    </div>
</nav>


    <!-- Video de fondo -->
    <video id="background-video" autoplay muted loop>
        <source src="..\img/clientes.mp4" type="video/mp4">
    </video>
    <div class="container">
        <h1>Listado de Clientes</h1>
        <div class="button-container">
            <button class="button" onclick="showAddForm()">Agregar Cliente</button> <!-- Botón para agregar cliente -->
        </div>
        <table id="clientes">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
            <!-- Conexión y consulta a la base de datos -->
            <?php include "../controladores/conexion.php"; ?>
            <?php
            // Parámetros de paginación
            $registrosPorPagina = 5; // Número de registros por página
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Página actual
            $inicio = ($paginaActual - 1) * $registrosPorPagina; // Índice inicial para la consulta

            // Consulta para obtener los registros de la página actual
$stmt = $pdo->prepare('SELECT * FROM clientes LIMIT :inicio, :registrosPorPagina');
$stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindParam(':registrosPorPagina', $registrosPorPagina, PDO::PARAM_INT);
$stmt->execute();
            while ($row = $stmt->fetch()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
                echo '<td>' . htmlspecialchars($row['direccion']) . '</td>';
                echo '<td>' . htmlspecialchars($row['telefono']) . '</td>';
                echo '<td>
                        <button onclick="showPurchaseHistory(' . htmlspecialchars(json_encode($row['id'])) . ')">Historial de Compra</button>
                    </td>';
                echo '</tr>';
            }
                        // Obtener el total de registros para calcular el número de páginas
                        $totalRegistros = $pdo->query('SELECT COUNT(*) FROM clientes')->fetchColumn();
                        $totalPaginas = ceil($totalRegistros / $registrosPorPagina);
            ?>
        </table>
        <div class="pagination">
    <?php if ($paginaActual > 1): ?>
        <a href="?pagina=<?= $paginaActual - 1 ?>">&laquo; Anterior</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <a href="?pagina=<?= $i ?>" class="<?= $i === $paginaActual ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($paginaActual < $totalPaginas): ?>
        <a href="?pagina=<?= $paginaActual + 1 ?>">Siguiente &raquo;</a>
    <?php endif; ?>
</div>
        <!-- Formulario para agregar/editar clientes -->
        <div id="formContainer">
            <h2 id="formTitle">Agregar Cliente</h2>
            <form id="clientForm" method="post" onsubmit="return submitForm()">
                <input type="hidden" id="clientId" name="clientId"> <!-- Campo oculto para el ID del cliente -->
                <input type="text" id="clientName" name="nombre" placeholder="Nombre" required>
                <input type="text" id="clientAddress" name="direccion" placeholder="Dirección" required>
                <input type="text" id="clientPhone" name="telefono" placeholder="Teléfono" required>
                <button type="submit">Guardar</button>
                <button type="button" onclick="cancelForm()">Cancelar</button>
            </form>
        </div>
    </div>

    <div class="home-button-container">
        <!-- Aquí puedes añadir contenido adicional -->
    </div>

    <script>
        // Función para mostrar el formulario de agregar cliente
        function showAddForm() {
            document.getElementById('formTitle').innerText = 'Agregar Cliente';
            document.getElementById('clientForm').action = '../controladores/save_client.php?action=add';
            document.getElementById('clientId').value = '';
            document.getElementById('clientName').value = '';
            document.getElementById('clientAddress').value = '';
            document.getElementById('clientPhone').value = '';
            document.getElementById('formContainer').style.display = 'block';
        }

        // Función para mostrar el formulario de editar cliente
        function showEditForm(id, name, address, phone) {
            document.getElementById('formTitle').innerText = 'Editar Cliente';
            document.getElementById('clientForm').action = '../controladores/save_client.php?action=edit';
            document.getElementById('clientId').value = id;
            document.getElementById('clientName').value = name;
            document.getElementById('clientAddress').value = address;
            document.getElementById('clientPhone').value = phone;
            document.getElementById('formContainer').style.display = 'block';
        }

    // Función para eliminar cliente de forma asíncrona
    function deleteClient(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
            fetch('..\controladores/save_client.php?action=delete', {
                method: 'POST',
                body: new URLSearchParams({ clientId: id })
            })
            .then(response => response.text())
            .then(data => {
                alert('Cliente eliminado exitosamente.');
                // Recargar la página para que el cliente desaparezca
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al eliminar el cliente.');
            });
        }
    }

        // Función para mostrar el historial de compras del cliente
        function showPurchaseHistory(id) {
            alert('Mostrar historial de compra del cliente con ID: ' + id);
            window.location.href = '../controladores/historial_com.php?client_id=' + id;
        }

        // Función para cancelar y ocultar el formulario
        function cancelForm() {
            document.getElementById('formContainer').style.display = 'none';
        }

        // Función para enviar el formulario mediante fetch
        function submitForm() {
            const form = document.getElementById('clientForm');
            const formData = new FormData(form);
            const action = form.getAttribute('action');
            fetch(action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la solicitud.');
            });

            return false; // Evita el envío tradicional del formulario
        }

        // Añade la clase 'active' al botón de navegación actual
        const buttons = document.querySelectorAll('.button');
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                buttons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });
    </script>
</body>
</html>