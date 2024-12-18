<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="icon" type="image/png" href="../img/thunderbikes.png">
    <link rel="stylesheet" href="styles.css"> <!-- Puedes mantener tus estilos aquí -->
    <style>
    /* Estilos globales */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: black;
        overflow-y: scroll;
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
        overflow: auto; /* Permitir desplazamiento dentro del contenedor */
    }
            /* Contenedor del formulario */
    #formContainer {
        display: none; /* Oculto por defecto */
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        z-index: 10;
    }
/* Ajuste de la tabla para desplazamiento horizontal */
table {
    width: 96.4%;
    border-collapse: collapse;
    margin: 20px 0;
    overflow-x: auto; /* Habilitar desplazamiento horizontal */
    display: block; /* Necesario para aplicar scroll en tablas largas */
    border-radius: 12px; /* Esquinas redondeadas */
    border: 1px solid #ddd; /* Opcional, para un borde más definido */
}


    th, td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
        white-space: nowrap; /* Evitar saltos de línea */
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
        overflow-x: auto; /* Habilitar barra de desplazamiento horizontal */
    }

    th, td {
        white-space: nowrap; /* Mantener el contenido sin saltos */
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
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
}

/* Estilo ajustado para el video de fondo */
#background-video {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    object-fit: cover; /* Ajusta el video sin distorsión */
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
#modalHistorial {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 10;
    }

    #modalHistorial .modal-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    #purchaseHistoryContent {
        max-height: 400px;
        overflow-y: auto;
        margin-bottom: 15px;
    }
    button {
    padding: 8px 12px;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    opacity: 0.8;
}

button[data-status="on"] {
    background-color: green;
}

button[data-status="off"] {
    background-color: red;
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

        #searchInput {
            width: 50%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        /* Contenedor de la paginación */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px 0;
    gap: 10px; /* Espacio entre los botones */
}

/* Botones de la paginación */
.pagination a {
    display: inline-block;
    padding: 8px 12px;
    background-color: #333;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

/* Botón activo */
.pagination a.active {
    background-color: goldenrod;
    color: black;
    font-weight: bold;
}

/* Efectos al pasar el cursor */
.pagination a:hover {
    background-color: wheat;
    transform: scale(1.05);
}

/* Deshabilitar los botones extremos si no son válidos */
.pagination a.disabled {
    background-color: #888;
    cursor: not-allowed;
    pointer-events: none;
}
    </style>
</head>
<body>
    <nav class="navtop">
        <div>
            <img src="../img/thunderbikes.png" alt="Thunderbikes" style="width: 50px; height: 50px;">
            <h1>THUNDERBIKE</h1>
        </div>
        <div>
        <a href="perfil.php" id="perfilBtn" class="button">Perfil</a>
        <a href="vendedor_dashboard.php" id="indexBtn" class="button">Inicio</a>
        <a href="productos.php" id="productosBtn" class="button">Productos</a>
        <a href="facturacion.php" id="facturacionBtn" class="button">Facturacion</a>
        </div>
    </nav>

    <video id="background-video" autoplay muted loop>
        <source src="../img/clientes.mp4" type="video/mp4">
    </video>

    <div class="container">
        <h1>Listado de Clientes</h1>
        <div class="button-container">
            <button class="button" onclick="showAddForm()">Agregar Cliente</button>
        </div>
        <input type="text" id="searchInput" placeholder="Buscar por nombre o documento" onkeyup="filterTable()">
        <table id="clientes">
            <tr>
                <th>ID</th>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Correo Electrónico</th>
                <th>Acciones</th>
            </tr>
            <?php 
            include "../controladores/conexion.php";
            $registrosPorPagina = 5;
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $inicio = ($paginaActual - 1) * $registrosPorPagina;

            $stmt = $pdo->prepare('SELECT * FROM clientes LIMIT :inicio, :registrosPorPagina');
            $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindValue(':registrosPorPagina', $registrosPorPagina, PDO::PARAM_INT);
            $stmt->execute();

            while ($row = $stmt->fetch()) {
                $estado = $row['estado'] ? 'On' : 'Off';
                $color = $row['estado'] ? 'green' : 'red';
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['numero_identificacion']) . '</td>';
                echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
                echo '<td>' . htmlspecialchars($row['direccion']) . '</td>';
                echo '<td>' . htmlspecialchars($row['telefono']) . '</td>';
                echo '<td>' . htmlspecialchars($row['correo']) . '</td>';
                echo '<td>
                        <button style="background-color: ' . $color . ';" 
                                onclick="toggleClientStatus(' . $row['id'] . ', ' . ($row['estado'] ? '0' : '1') . ', this)">
                            ' . $estado . '
                        </button>
                        <button onclick="showEditForm(' . htmlspecialchars(json_encode($row['id'])) . ', ' . htmlspecialchars(($row['numero_identificacion'])) . ', ' . htmlspecialchars(json_encode($row['nombre'])) . ', ' . htmlspecialchars(json_encode($row['direccion'])) . ', ' . htmlspecialchars(json_encode($row['telefono'])) . ',' . htmlspecialchars(json_encode($row['correo'])) . ')">Editar</button>
                        <button onclick="showPurchaseHistory(' . htmlspecialchars(json_encode($row['id'])) . ')">Historial de Compra</button>
                        </td>';
                echo '</tr>';
            }

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
    </div>
<!-- El formulario para agregar/editar clientes -->
<div id="formContainer">
    <h2 id="formTitle">Agregar Cliente</h2>
    <form id="clientForm" method="post" action="../controladores/save_client.php?action=add">
    <input type="hidden" id="clientId" name="clientId"> <!-- Campo oculto para el ID del cliente -->
    <input type="text" id="clientDocumen" name="numero_identificacion" placeholder="Documento" required>
    <input type="text" id="clientName" name="nombre" placeholder="Nombre" required>
    <input type="text" id="clientAddress" name="direccion" placeholder="Dirección" required>
    <input type="text" id="clientPhone" name="telefono" placeholder="Teléfono" required>
    <input type="text" id="clientEmail" name="correo" placeholder="Correo Electrónico" required>
    <button type="submit">Guardar</button>
    <button type="button" onclick="cancelForm()">Cancelar</button>
</form>
</div>
    </div>
    <div id="modalHistorial" style="display: none;">
    <div class="modal-content">
        <h2>Historial de Compras</h2>
        <div id="purchaseHistoryContent">
            <!-- Aquí se cargará dinámicamente el contenido -->
        </div>
        <button onclick="closeHistoryModal()">Cerrar</button>
    </div>
</div>


    <script>
        function filterTable() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("clientes");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) { // Empieza desde 1 para omitir la fila del encabezado
        const documento = rows[i].getElementsByTagName("td")[1]?.textContent.toLowerCase();
        const nombre = rows[i].getElementsByTagName("td")[2]?.textContent.toLowerCase();

        if (documento?.includes(filter) || nombre?.includes(filter)) {
            rows[i].style.display = ""; // Mostrar la fila
        } else {
            rows[i].style.display = "none"; // Ocultar la fila
        }
    }
}

function showAddForm() {
    // Cambiar el título del formulario
    document.getElementById('formTitle').innerText = 'Agregar Cliente';

    // Limpiar los campos del formulario
    document.getElementById('clientForm').reset();

    // Establecer la acción para agregar un cliente
    document.getElementById('clientForm').action = '../controladores/save_client.php?action=add';

    // Asegurarse de que los campos sean editables
    document.getElementById('clientDocumen').readOnly = false;
    document.getElementById('clientName').readOnly = false;

    // Ocultar el campo de ID, ya que no es necesario para agregar
    document.getElementById('clientId').value = '';

    // Mostrar el formulario
    document.getElementById('formContainer').style.display = 'block';
}
    // Mostrar formulario de editar cliente
    function showEditForm(id, documento, name, address, phone, email) {
    // Cambiar el título del formulario
    document.getElementById('formTitle').innerText = 'Editar Cliente';

    // Cambiar la acción del formulario
    document.getElementById('clientForm').action = '../controladores/save_client.php?action=edit';

    // Rellenar los campos con los datos del cliente
    document.getElementById('clientId').value = id;
    document.getElementById('clientDocumen').value = documento;
    document.getElementById('clientName').value = name;
    document.getElementById('clientAddress').value = address;
    document.getElementById('clientPhone').value = phone;
    document.getElementById('clientEmail').value = email;

    // Hacer que los campos Documento y Nombre sean solo lectura
    document.getElementById('clientDocumen').readOnly = true;
    document.getElementById('clientName').readOnly = true;

    // Mostrar el formulario
    document.getElementById('formContainer').style.display = 'block';
}
    // Función para mostrar el modal con el historial de compras
    function showPurchaseHistory(clientId) {
        // Mostrar el modal
        const modal = document.getElementById('modalHistorial');
        modal.style.display = 'flex';

        // Cargar dinámicamente el historial de compras
        const historyContent = document.getElementById('purchaseHistoryContent');
        historyContent.innerHTML = 'Cargando...';

        fetch(`controladores/historial_compras.php?client_id=${clientId}`)
            .then(response => response.text())
            .then(data => {
                historyContent.innerHTML = data;
            })
            .catch(error => {
                console.error('Error al cargar el historial:', error);
                historyContent.innerHTML = 'No se pudo cargar el historial.';
            });
    }

    // Función para cerrar el modal del historial de compras
    function closeHistoryModal() {
        document.getElementById('modalHistorial').style.display = 'none';
    }
    // Cancelar formulario
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

        function toggleClientStatus(id, newState, button) {
    fetch('update_client_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&estado=${newState}`
    })
    .then(response => {
        if (response.ok) {
            const newStatus = newState === 1 ? 'On' : 'Off';
            const newColor = newState === 1 ? 'green' : 'red';
            button.textContent = newStatus;
            button.style.backgroundColor = newColor;
            button.setAttribute('onclick', `toggleClientStatus(${id}, ${newState === 1 ? 0 : 1}, this)`);
        } else {
            alert('Error al actualizar el estado del cliente');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al cambiar el estado.');
    });
}
function submitForm() {
    const form = document.getElementById('clientForm');
    const formData = new FormData(form);
    
    const clientDoc = document.getElementById('clientDocumen').value;
    const clientName = document.getElementById('clientName').value;
    
    // Verificar si los campos de nombre o documento son modificados
    if (formData.get('numero_identificacion') !== clientDoc || formData.get('nombre') !== clientName) {
        alert("No es posible realizar el cambio del nombre o documento del cliente.");
        return false;  // Evita que el formulario se envíe
    }

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


    </script>
    <script>
    function showSuccessAlert() {
        const alert = document.getElementById('successAlert');
        alert.style.display = 'block';
        setTimeout(() => {
            alert.style.display = 'none';
        }, 3000); // Ocultar después de 3 segundos
    }
</script>
<script>
    // Verifica si hay un parámetro de éxito en la URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'success') {
        showSuccessAlert();
    }
</script>


    <!-- Alerta dinámica -->
<div id="successAlert" style="display: none; position: fixed; top: 20px; right: 20px; background: green; color: white; padding: 15px; border-radius: 5px; z-index: 1000;">
    Cliente agregado exitosamente
</div>

</body>
</html>