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
        background-color: transparent;
        display: flex;
        flex-direction: column; /* Permite que el contenido fluya en una sola columna */
        justify-content: flex-start; /* Alinea los elementos al inicio */
        align-items: center;
        min-height: 100vh; /* Altura mínima para contenido completo */
        overflow-y: auto; /* Habilita el scroll vertical si el contenido es más alto que la pantalla */
    }

    #background-video {
        position: fixed;
        top: 0;
        left: 0;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        z-index: -1; /* Detrás de todo el contenido */
    }

    form {
        background-color: rgba(255, 255, 255, 0.9); /* Fondo blanco semitransparente */
        padding: 30px;
        border-radius: 10px;
        width: 100%;
        max-width: 400px;
        margin: 50px auto; /* Espacio superior e inferior */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Sombra para resaltar */
    }

    form h2 {
        color: gold;
        margin-bottom: 20px;
        text-align: center;
        font-size: 24px;
    }

    form img {
        display: block;
        margin: 0 auto 20px; /* Centra la imagen */
        max-width: 150px; /* Controla el tamaño */
        border-radius: 50%; /* Hazla circular si es necesario */
    }

    form input[type="text"],
    form input[type="email"],
    form input[type="password"],
    form select {
        width: calc(100% - 20px); /* Ajusta con respecto al padding */
        padding: 10px;
        margin: 10px 0;
        border: none;
        border-radius: 5px;
        border: 1px solid #ccc;
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

    .message {
        text-align: center;
        font-size: 16px;
        color: green;
    }

    .errors {
        color: red;
        font-size: 14px;
        margin-bottom: 20px;
    }
</style>

</head>
<body>
    <!-- Código del video -->
    <video id="background-video" autoplay muted loop>
        <source src="img/truco.mp4" type="video/mp4">
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
            <label>Contraseña</label>
            <input type="password" name="password_1">
        </div>
        <div class="input-group">
            <label>Confirmar Contraseña</label>
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