<?php include('servidor.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registro</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="icon" type="thundrbike/png" href="img/thunderbike.png">
  <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        overflow: hidden; /* Evitar barras de desplazamiento */
        background-color: transparent; /* Hacer el fondo transparente */
        display: flex; /* Utilizar flexbox */
        justify-content: center; /* Centrar horizontalmente */
        align-items: center; /* Centrar verticalmente */
        height: 100vh; /* Tamaño de la ventana visible */
    }

    /* Estilos para el video de fondo */
    #background-video {
        position: fixed; /* Fijar el video en la pantalla */
        top: 50%;
        left: 50%;
        min-width: 100%; /* Abarcar toda la pantalla */
        min-height: 100%;
        width: auto;
        height: auto;
        transform: translate(-50%, -50%); /* Centrar el video */
        z-index: -1; /* Colocar el video detrás de otros elementos */
    }

    form {
        background-color: rgba(255, 255, 255, 0.9); /* Fondo semi-transparente */
        padding: 30px;
        border-radius: 10px;
        width: 320px;
        margin: 0 auto; /* Centrar horizontalmente */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra suave */
    }

    form h2 {
        color: gold; /* Letras doradas */
        margin-bottom: 20px; /* Espacio inferior */
        text-align: center; /* Centrado */
        position: relative; /* Para posicionar correctamente la imagen */
        font-size: 24px; /* Tamaño de fuente */
    }

    form h2 img {
        position: absolute; /* Posición absoluta con respecto al título */
        top: -40px; /* Ajuste de posición vertical */
        left: calc(50% - 20px); /* Ajuste de posición horizontal */
        width: 80px; /* Ancho de la imagen */
        height: auto; /* Alto de la imagen automático */
    }

    form input[type="text"],
    form input[type="email"],
    form input[type="password"],
    form select {
        width: calc(100% - 20px); /* Ancho del 100% menos el padding */
        padding: 10px;
        margin: 10px 0;
        border: none;
        border-radius: 5px;
        box-sizing: border-box;
        border: 1px solid #ccc; /* Borde gris */
    }

    form .btn {
        width: 100%;
        padding: 10px;
        margin-top: 20px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
    }
  </style>
</head>
<body>
    <!-- Código del video -->
    <video id="background-video" autoplay muted loop>
        <source src="img/truco.mp4" type="video/mp4">
        Tu navegador no admite la etiqueta de video.
    </video>

    <form method="post" action="registro.php">
        <?php include('errores.php'); ?>
        <h2 style="color: gold;">Registro Thunderbike</h2>
        <div style="text-align: center;"> <!-- Centrar la imagen del logo -->
            <img src="img/thunderbikes.png" alt="Thunderbike Logo" style="width: 100px; height: auto;">
        </div>
        <div class="input-group">
            <label>Nombre Usuario</label>
            <input type="text" name="username" value="<?php echo $username; ?>">
        </div>
        <div class="input-group">
            <label>Correo</label>
            <input type="email" name="email" value="<?php echo $email; ?>">
        </div>
        <div class="input-group">
            <label>Contraceña</label>
            <input type="password" name="password_1">
        </div>
        <div class="input-group">
            <label>Confirmar Contraceña</label>
            <input type="password" name="password_2">
        </div>
        <div class="input-group">
            <label>Seleccione Cargo a ejercer:</label>
            <select name="role">
                <option value="vendedor">Vendedor</option>
                <option value="mecanico">Mecanico</option>
            </select>
        </div>
        <div class="input-group">
            <button type="submit" class="btn" name="reg_user">Registrar</button>
        </div>
        <p>
            ¿Ya eres usuario? <a href="logeo.php">Iniciar sesión</a>
        </p>
    </form>
    <footer style="position: fixed; bottom: 0; width: 100%; color: #ffc600;">
    <center>V1.0.1 © Todos los derechos reservados. Thunderbike</center>
</footer>
</body>
</html>
