<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores</title>
    <link rel="icon" type="thundrbikes.png" href="img/thunderbikes.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-image: url('https://wallpaperaccess.com/full/5651708.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            padding: 50px 0 0 0;
        }

        .navtop {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 10px 0;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 999;
        }

        .navtop a {
            color: black;
            text-decoration: none;
            margin: 0 15px;
        }

        .navtop a:hover {
            color: goldenrod;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            margin: auto;
            width: 60%;
            max-width: 750px;
            border-radius: 5px;
            position: relative;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 100px 0;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .button-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .button-container .button {
            margin-left: auto;
        }

        .button:hover {
            color: goldenrod;
            background-color: rgba(255, 255, 255, 0.2);
        }

        #formContainer {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }

        #formTitle {
            margin-top: 0;
        }

        .home-button-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        /* Estilos para el modal del historial */
        #historyModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        #historyModal .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 800px;
        }

        #historyModal .modal-content table {
            width: 100%;
            border-collapse: collapse;
        }

        #historyModal .modal-content th, #historyModal .modal-content td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #historyModal .modal-content th {
            background-color: #f2f2f2;
        }

        #historyModal .modal-content button {
            float: right;
            background-color: red;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        #historyModal .modal-content button:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <nav class="navtop">
        <div>
            <div class="container">
                <div class="button-container">
                    <!-- Botones de navegación -->
                    <a href="inicio.php" id="indexBtn" class="button">Inicio</a>
                    <a href="perfil.php" id="perfilBtn" class="button">Perfil</a>
                    <a href="clientes.php" id="clientesBtn" class="button">Clientes</a>
                    <a href="productos.php" id="productosBtn" class="button">Productos</a>
                    <a href="proveedores.php" id="proveedoresBtn" class="button active">Proveedores</a>
                    <a href="ventas.php" id="ventasBtn" class="button">Ventas</a>
                    <a href="reparaciones.php" id="reparacionesBtn" class="button">Reparaciones</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Video de fondo -->
    <video id="background-video" autoplay muted loop>
        <source src="img/proveedores.mp4" type="video/mp4">
        Tu navegador no admite la etiqueta de video.
    </video>

    <div class="container">
        <h1>Listado de Proveedores</h1>
        <div class="button-container">
            <button class="button" onclick="showAddForm()">Agregar Proveedor</button> <!-- Botón para agregar proveedor -->
        </div>
        <table id="proveedores">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
            <?php include "controladores/conexion.php"; ?>
            <?php
            $stmt = $pdo->query('SELECT * FROM proveedores');
            while ($row = $stmt->fetch()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
                echo '<td>' . htmlspecialchars($row['direccion']) . '</td>';
                echo '<td>' . htmlspecialchars($row['telefono']) . '</td>';
                echo '<td>
                        <button onclick="showEditForm(' . htmlspecialchars(json_encode($row['id'])) . ', ' . htmlspecialchars(json_encode($row['nombre'])) . ', ' . htmlspecialchars(json_encode($row['direccion'])) . ', ' . htmlspecialchars(json_encode($row['telefono'])) . ')">Editar</button>
                        <button onclick="deleteProveedor(' . htmlspecialchars(json_encode($row['id'])) . ')">Eliminar</button>
                        <button onclick="showHistory(' . htmlspecialchars(json_encode($row['id'])) . ')">Historial</button>
                    </td>';
                echo '</tr>';
            }
            ?>
        </table>

        <!-- Formulario para agregar/editar proveedores -->
        <div id="formContainer">
            <h2 id="formTitle">Agregar Proveedor</h2>
            <form id="proveedorForm" method="post" onsubmit="return submitForm()">
                <input type="hidden" id="proveedorId" name="proveedorId"> <!-- Campo oculto para el ID del proveedor -->
                <input type="text" id="proveedorName" name="nombre" placeholder="Nombre" required>
                <input type="text" id="proveedorAddress" name="direccion" placeholder="Dirección" required>
                <input type="text" id="proveedorPhone" name="telefono" placeholder="Teléfono" required>
                <button type="submit">Guardar</button>
                <button type="button" onclick="cancelForm()">Cancelar</button>
            </form>
        </div>

        <!-- Modal para el historial de reparaciones -->
        <div id="historyModal">
            <div class="modal-content">
                <h2>Historial de Reparaciones</h2>
                <button onclick="closeHistoryModal()">Cerrar</button>
                <table id="historyTable">
                    <thead>
                        <tr>
                            <th>Proveedor</th>
                            <th>Producto</th>
                            <th>Descripción de la Reparación</th>
                            <th>Fecha de Reparación</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="home-button-container">
        <!-- Aquí puedes añadir contenido adicional -->
    </div>

    <script>
        function showAddForm() {
            document.getElementById('formTitle').innerText = 'Agregar Proveedor';
            document.getElementById('proveedorForm').action = 'controladores/save_proveedor.php?action=add';
            document.getElementById('proveedorId').value = '';
            document.getElementById('proveedorName').value = '';
            document.getElementById('proveedorAddress').value = '';
            document.getElementById('proveedorPhone').value = '';
            document.getElementById('formContainer').style.display = 'block';
        }

        function showEditForm(id, name, address, phone) {
            document.getElementById('formTitle').innerText = 'Editar Proveedor';
            document.getElementById('proveedorForm').action = 'controladores/save_proveedor.php?action=edit';
            document.getElementById('proveedorId').value = id;
            document.getElementById('proveedorName').value = name;
            document.getElementById('proveedorAddress').value = address;
            document.getElementById('proveedorPhone').value = phone;
            document.getElementById('formContainer').style.display = 'block';
        }

        function cancelForm() {
            document.getElementById('formContainer').style.display = 'none';
        }

        function submitForm() {
            // Lógica para enviar el formulario
            return true;
        }

        function deleteProveedor(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este proveedor?')) {
                window.location.href = 'controladores/delete_proveedor.php?id=' + id;
            }
        }

        function showHistory(id) {
            document.getElementById('historyModal').style.display = 'flex';

            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'controladores/historial_proveedor.php?id=' + id, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    const tbody = document.getElementById('historyTable').getElementsByTagName('tbody')[0];
                    tbody.innerHTML = '';

                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${row.proveedor}</td>
                            <td>${row.producto}</td>
                            <td>${row.descripcion}</td>
                            <td>${row.fecha_reparacion}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            };
            xhr.send();
        }

        function closeHistoryModal() {
            document.getElementById('historyModal').style.display = 'none';
        }
    </script>
</body>
</html>
