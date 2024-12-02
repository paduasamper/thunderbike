<?php
// Datos de conexión (ajusta según tu configuración)
$host = 'localhost';
$dbname = 'thunderbike';
$username = 'root';
$password = '';

try {
    // Crear la conexión con PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configurar PDO para lanzar excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consultas para obtener los totales
    $ventasStmt = $pdo->query("SELECT COUNT(*) as totalVentas FROM ventas");
    $productosStmt = $pdo->query("SELECT COUNT(*) as totalProductos FROM productos");
    $reparacionesStmt = $pdo->query("SELECT COUNT(*) as totalReparaciones FROM reparaciones");
    $clientesStmt = $pdo->query("SELECT COUNT(*) as totalClientes FROM clientes");

    // Obtener los resultados
    $totalVentas = $ventasStmt->fetch(PDO::FETCH_ASSOC)['totalVentas'];
    $totalProductos = $productosStmt->fetch(PDO::FETCH_ASSOC)['totalProductos'];
    $totalReparaciones = $reparacionesStmt->fetch(PDO::FETCH_ASSOC)['totalReparaciones'];
    $totalClientes = $clientesStmt->fetch(PDO::FETCH_ASSOC)['totalClientes'];

} catch (PDOException $e) {
    // En caso de error, mostrar un mensaje
    echo "Error en la conexión: " . $e->getMessage();
}
?>

<?php
session_start();

// Configurar headers para evitar cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['username'])) {
    header('location: logeo.php');
    exit();
}

$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>THUNDERBIKE</title>
    <link rel="icon" type="image/png" href="img/thunderbikes.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    /* Normalización de márgenes y padding */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box; /* Para que el padding y el border no afecten al tamaño total */
    }

    /* Fuente personalizada y responsiva */
    body {
        font-size: 16px;
        background: #f5f5f5;
        font-family: 'Arial', sans-serif; /* Cambia a la tipografía deseada */
        line-height: 1.6;
    }

    /* Cambiar la tipografía y hacerla responsiva */
    .header {
        width: 60%;
        margin: 40px auto 0;
        color: white;
        background: #5F9EA0;
        text-align: center;
        border: 1px solid #B0C4DE;
        border-bottom: none;
        border-radius: 10px 10px 0 0;
        padding: 20px;
        font-family: 'Roboto', sans-serif; /* Cambiar a la tipografía deseada */
        font-size: 2rem; /* Responsivo */
    }

    form, .content {
        width: 70%;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #B0C4DE;
        background: white;
        border-radius: 10px 0 10px 10px;
        font-family: 'Roboto', sans-serif;
        font-size: 1.2rem; /* Responsivo */
    }

    .input-group {
        margin: 10px 0;
    }

    .input-group label {
        display: block;
        text-align: left;
        margin: 3px;
        font-size: 1rem;
    }

    .input-group input {
        height: 30px;
        width: 100%; /* Asegura que los campos de entrada ocupen el ancho completo */
        padding: 5px 10px;
        font-size: 1rem;
        border-radius: 5px;
        border: 1px solid gray;
    }

    .btn {
        padding: 10px;
        font-size: 1rem;
        color: rgb(12, 12, 12);
        background: #ffc600;
        border: none;
        border-radius: 5px;
    }

    .error {
        width: 92%; 
        margin: 0 auto; 
        padding: 10px; 
        border: 1px solid #a94442; 
        color: #a94442; 
        background: #f2dede; 
        border-radius: 5px; 
        text-align: left;
    }

    .success {
        color: #3c763d; 
        background: #dff0d8; 
        border: 1px solid #3c763d;
        margin-bottom: 20px;
    }

    .navtop {
        background-color: #2f3947;
        height: 60px;
        width: 100%;
        border: 0;
    }

    .navtop div {
        display: flex;
        margin: 0 auto;
        width: 100%;
        height: 100%;
        max-width: 1200px; /* Limitar el ancho en pantallas grandes */
    }

    .navtop div h1, .navtop div a {
        display: inline-flex;
        align-items: center;
    }

    .navtop div h1 {
        flex: 1;
        font-size: 1.5rem; /* Responsivo */
        color: #eaebed;
        font-weight: normal;
    }

    .navtop div a {
        padding: 0 20px;
        text-decoration: none;
        color: #E5A65E;
        font-weight: bold;
        position: relative;
        font-size: 1rem; /* Responsivo */
    }

    .navtop div a:hover {
        color: #eaebed;
    }

    .button-selected {
        background-color: gold;
    }

    .button:hover {
        background-color: lightblue;
    }

    #cerrarSesionBtn:hover {
        color: red;
    }

    #background-video {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        object-fit: cover;
    }

    /* Añadir media queries para pantallas pequeñas */
    @media (max-width: 768px) {
        .navtop div {
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .navtop div h1 {
            text-align: center;
            font-size: 1.3rem; /* Ajustar el tamaño de fuente para pantallas pequeñas */
        }

        .button-container {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .button {
            width: 100%; /* Los botones ocupan todo el ancho en pantallas pequeñas */
            text-align: center;
            padding: 10px;
            margin-bottom: 5px;
        }

        .header {
            width: 100%;
            font-size: 1.8rem; /* Responsivo */
        }

        form, .content {
            width: 100%;
            padding: 10px;
            font-size: 1rem; /* Responsivo */
        }

        .input-group input {
            width: 100%; /* Asegura que los inputs ocupen todo el ancho */
        }
    }

    /* Ajustes adicionales para pantallas aún más pequeñas */
    @media (max-width: 480px) {
        body {
            font-size: 14px; /* Reduce el tamaño de fuente en pantallas muy pequeñas */
        }

        .header, form, .content {
            padding: 10px;
            font-size: 1rem; /* Responsivo */
        }

        .navtop div h1 {
            font-size: 1.2rem; /* Responsivo */
        }

        .navtop div a {
            font-size: 0.9rem; /* Reducir tamaño de fuente */
        }
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
                    <a href="reparaciones.php" id="reparacionesBtn" class="button">Reparaciones</a>
                    <a href="facturacion.php" id="reparacionesBtn" class="button">Facturacion</a>
                <a href="logout.php" id="cerrarSesionBtn" class="button special">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</nav>

<div>
    <h1 style="color: gold;">Bienvenido al Panel de <?= ucfirst($role) ?></h1>
    <p style="color: gold;">Esta es una página exclusiva para <?= $role === 'administrador' ? 'administradores' : ($role === 'mecanico' ? 'mecánicos' : 'vendedores') ?>.</p>
</div>

<video id="background-video" autoplay muted loop>
    <source src="img/salto2.mp4" type="video/mp4">
    Tu navegador no admite la etiqueta de video.
</video>

<?php if (isset($_SESSION['success'])) : ?>
    <div class="error success">
        <h3>
            <?php 
            echo $_SESSION['success']; 
            unset($_SESSION['success']);
            ?>
        </h3>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['username'])) : ?>
    <p><strong style="font-size: 24px; color: #efb810;"><?php echo $_SESSION['username']; ?></strong></p>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si hay un usuario activo
        const username = "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>";
        
        if (!username) {
            // Redirigir si no hay sesión activa
            window.location.href = 'logeo.php';
        }

        const productosBtn = document.getElementById('productosBtn');
        const productosTooltip = document.getElementById('productosTooltip');

        // Mostrar tooltip
        productosBtn.addEventListener('mouseenter', () => {
            productosTooltip.style.display = 'block';
        });

        // Ocultar tooltip
        productosBtn.addEventListener('mouseleave', () => {
            productosTooltip.style.display = 'none';
        });

        // Mantener el tooltip visible al pasar el mouse sobre él
        productosTooltip.addEventListener('mouseenter', () => {
            productosTooltip.style.display = 'block';
        });

        productosTooltip.addEventListener('mouseleave', () => {
            productosTooltip.style.display = 'none';
        });

        // Cerrar sesión
        const logoutButton = document.getElementById('cerrarSesionBtn');
        logoutButton.addEventListener('mouseenter', () => {
            logoutButton.style.color = 'red';
        });
        logoutButton.addEventListener('mouseleave', () => {
            logoutButton.style.color = 'white';
        });

        // Selección de botones
        const buttons = document.querySelectorAll('.button');
        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                buttons.forEach(function(btn) {
                    btn.classList.remove('button-selected');
                });
                button.classList.add('button-selected');
            });
        });
    });
</script>
<div class="content">
    <canvas id="myChart"></canvas>
</div>

<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar', // Tipo de gráfico: barra
        data: {
            labels: ['Ventas', 'Insumos', 'Reparaciones', 'Clientes'], // Etiquetas
            datasets: [{
                label: 'Estadísticas',
                data: [<?= $totalVentas ?>, <?= $totalProductos ?>, <?= $totalReparaciones ?>, <?= $totalClientes ?>], // Datos de PHP
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)' // Color para clientes
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)' // Borde para clientes
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuToggle = document.querySelector('.menu-toggle');
        const menuContainer = document.querySelector('.menu-container');

        menuToggle.addEventListener('click', function () {
            menuContainer.classList.toggle('active'); // Alternar el menú desplegable
        });
    });
</script>


</body>
</html>

