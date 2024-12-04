<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header('location: logeo.php'); // Redirigir a la página de inicio de sesión
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Si el rol no es 'vendedor', redirigir a la página correspondiente
if ($role != 'vendedor') {
    header('Location: index.php'); // Cambia 'index.php' según tu lógica
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Vendedor</title>
    <link rel="icon" type="image/png" href="../img/thunderbikes.png">
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
        <img src="../img/thunderbikes.png" alt="thunderbikes" style="width: 55px; height: 55px;">
        <h1>THUNDERBIKE</h1>
        <div class="container">
            <div class="button-container">
                <a href="perfil.php" id="perfilBtn" class="button">Perfil</a>
                    <a href="client.php" id="clientesBtn" class="button">Clientes</a>
                    <a href="productos.php" id="productosBtn" class="button">Productos</a>
                    <a href="facturacion.php" id="facturacionBtn" class="button">Facturacion</a>
                <a href="../logout.php" id="cerrarSesionBtn" class="button special">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</nav>

<div>
    <h1 style="color: gold;">Bienvenido al Panel de <?= ucfirst($role) ?><br><?php echo htmlspecialchars($username); ?>!</h1>
    <p style="color: gold;">Esta es una página exclusiva para <?= $role === 'administrador' ? 'administradores' : ($role === 'mecanico' ? 'mecánicos' : 'vendedores') ?>.</p>
</div>

  <video id="background-video" autoplay muted loop>
    <source src="../img/salto2.mp4" type="video/mp4">
  </video>
</body>
</html>



