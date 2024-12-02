<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Proveedores</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos globales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: black;
            overflow: hidden;
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
            overflow: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            overflow-x: auto;
            display: block;
        }
        .table-container {
    max-width: 90%; /* Limita el ancho del contenedor al 90% del viewport */
    overflow-x: auto; /* Habilita el desplazamiento horizontal si es necesario */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Agrega un sombreado elegante */
    background-color: #fff; /* Fondo blanco para contraste */
    border-radius: 8px; /* Bordes redondeados */
    padding: 20px;
}

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

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
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover,
        button[type="button"]:hover {
            background-color: #218838;
        }

    </style>
</head>
<body>
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
        </div>
    </nav>

    <div class="container">
        <h1>Listado de Proveedores</h1>
        <div class="button-container">
            <button class="button" onclick="showAddForm()">Agregar Proveedor</button>
        </div>
        <?php
        include "controladores/conexion.php";
        try {
            $stmt = $pdo->query('SELECT id, nombre, direccion, telefono, nit FROM proveedores');
        } catch (PDOException $e) {
            die('Error en la consulta SQL: ' . $e->getMessage());
        }
        ?>
        <table id="proveedores">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>NIT</th>
                <th>Acciones</th>
            </tr>
            <?php
if (isset($stmt)) {
    while ($row = $stmt->fetch()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
        echo '<td>' . htmlspecialchars($row['direccion']) . '</td>';
        echo '<td>' . htmlspecialchars($row['telefono']) . '</td>';
        echo '<td>' . htmlspecialchars($row['nit']) . '</td>';
        echo '<td>
        <button onclick="editProvider(' . htmlspecialchars($row['id']) . ')">Editar</button>
        <button class="status-btn" data-id="' . htmlspecialchars($row['id']) . '" data-status="off" onclick="toggleStatus(this)">OFF</button>
        </td>';
        echo '</tr>';
    }
}
            ?>
        </table>

        <!-- Formulario para agregar/editar proveedores -->
        <div id="formContainer">
            <h2 id="formTitle">Agregar Proveedor</h2>
            <form id="proveedorForm" method="post" onsubmit="return submitForm()">
                <input type="hidden" id="proveedorId" name="proveedorId">
                <label for="proveedorName">Nombre:</label>
                <input type="text" id="proveedorName" name="nombre" placeholder="Nombre" required readonly>

                <label for="proveedorAddress">Dirección:</label>
                <input type="text" id="proveedorAddress" name="direccion" placeholder="Dirección" required>

                <label for="proveedorPhone">Teléfono:</label>
                <input type="text" id="proveedorPhone" name="telefono" placeholder="Teléfono" required pattern="\d+" title="El número de teléfono debe contener solo dígitos">
                
                <label for="proveedorNit">NIT:</label>
                <input type="text" id="proveedorNit" name="nit" placeholder="NIT" required readonly>

                <button type="submit">Guardar</button>
                <button type="button" onclick="cancelForm()">Cancelar</button>
            </form>
        </div>

    </div>

    <script>
function showAddForm() {
    document.getElementById('formTitle').innerText = 'Agregar Proveedor';
    document.getElementById('proveedorForm').action = 'controladores/save_proveedor.php?action=add';
    
    // Limpiar los campos y habilitarlos para edición
    document.getElementById('proveedorId').value = '';
    document.getElementById('proveedorName').value = '';
    document.getElementById('proveedorName').readOnly = false; // Habilitar campo para agregar
    document.getElementById('proveedorAddress').value = '';
    document.getElementById('proveedorPhone').value = '';
    document.getElementById('proveedorNit').value = '';
    document.getElementById('proveedorNit').readOnly = false; // Habilitar campo para agregar

    // Mostrar el formulario
    document.getElementById('formContainer').style.display = 'block';
}
        function editProvider(id) {
    const rows = document.querySelectorAll('#proveedores tr');
    let selectedRow = null;

    rows.forEach((row) => {
        const rowId = row.children[0]?.innerText; // Obtener el valor de la primera columna (ID)
        if (rowId == id) {
            selectedRow = row;
        }
    });

    if (!selectedRow) {
        alert('Proveedor no encontrado.');
        return;
    }

    // Extraer información de la fila seleccionada
    const nombre = selectedRow.children[1]?.innerText;
    const direccion = selectedRow.children[2]?.innerText;
    const telefono = selectedRow.children[3]?.innerText;
    const nit = selectedRow.children[4]?.innerText;

    // Llenar el formulario con la información del proveedor
    document.getElementById('proveedorId').value = id;
    document.getElementById('proveedorName').value = nombre;
    document.getElementById('proveedorAddress').value = direccion;
    document.getElementById('proveedorPhone').value = telefono;
    document.getElementById('proveedorNit').value = nit;

    // Bloquear edición en ciertos campos
    document.getElementById('proveedorName').readOnly = true;
    document.getElementById('proveedorNit').readOnly = true;

    // Cambiar título del formulario y acción
    document.getElementById('formTitle').innerText = 'Editar Proveedor';
    document.getElementById('proveedorForm').action = 'controladores/save_proveedor.php?action=edit';

    // Mostrar el formulario
    document.getElementById('formContainer').style.display = 'block';
}

        function cancelForm() {
            document.getElementById('formContainer').style.display = 'none';
        }

        function submitForm() {
            // Lógica de envío de formulario
            return true;
        }
        function toggleStatus(button) {
    const providerId = button.getAttribute('data-id');
    const currentStatus = button.getAttribute('data-status');
    const newStatus = currentStatus === 'off' ? 'on' : 'off';

    // Cambiar el estado visualmente
    button.setAttribute('data-status', newStatus);
    button.textContent = newStatus.toUpperCase();
    button.style.backgroundColor = newStatus === 'on' ? 'green' : 'red';

    // Llamar al servidor para actualizar el estado
    fetch(`controladores/update_proveedor_status.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: providerId, status: newStatus }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (!data.success) {
                alert('Error al actualizar el estado.');
                // Restaurar el estado anterior
                button.setAttribute('data-status', currentStatus);
                button.textContent = currentStatus.toUpperCase();
                button.style.backgroundColor = currentStatus === 'on' ? 'green' : 'red';
            }
        })
        .catch((error) => {
            alert('Error al comunicarse con el servidor.');
            console.error(error);
        });
}

    </script>
</body>
</html>
