<?php include('servidor.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>INICIAR SESION</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="icon" type="image/png" href="img/thunderbikes.png">
  <style>
    /* Estilos para el video de fondo */
    #background-video {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: -1;
    }

    .form-wrapper {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
      border-radius: 10px;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 90%;
      max-width: 400px;
      box-sizing: border-box;
    }

    .header img {
      width: 100%;
      max-width: 190px;
      height: auto;
      margin: 0 auto;
      display: block;
    }

    .input-group {
      margin-bottom: 15px;
    }

    .input-group label {
      display: block;
      margin-bottom: 5px;
    }

    .input-group input, .input-group select {
      width: 100%;
      padding: 10px;
      box-sizing: border-box;
    }

    .btn {
      width: 100%;
      padding: 10px;
      background-color: #ffc600;
      border: none;
      cursor: pointer;
    }

    footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      color: #ffc600;
      background-color: rgba(0, 0, 0, 0.5);
      padding: 10px 0;
    }

    @media (min-width: 600px) {
      .form-wrapper {
        width: 80%;
        max-width: 400px;
      }
    }
  </style>
</head>
<body>
<!-- Código del video -->
<video id="background-video" autoplay muted loop>
  <source src="img/lujo.mp4" type="video/mp4">
  Tu navegador no admite la etiqueta de video.
</video>
<div class="form-wrapper">
  <div class="header">
    <img src="img/thunderbikes.png" alt="thunderbike">
    <h2 class="animate__animated animate__bounce" style="color: black;">INICIAR SESION</h2>
  </div>
  <form method="post" action="logeo.php">
    <?php include('errores.php'); ?>
    <div class="input-group">
      <label>Nombre Usuario o Correo: </label>
      <input type="text" name="username">
    </div>
    <div class="input-group">
      <label>Clave: </label>
      <input type="password" name="password">
    </div>
    <div class="input-group">
    <script>
    // Función para alternar entre mostrar/ocultar la contraseña al hacer clic en el icono del ojo
    var passwordToggles = document.querySelectorAll(".password-toggle");
    passwordToggles.forEach(function(toggle) {
      toggle.addEventListener("click", function() {
        var passwordInput = this.previousElementSibling;
        if (passwordInput.type === "password") {
          passwordInput.type = "text";
          this.classList.remove("fa-eye-slash");
          this.classList.add("fa-eye");
        } else {
          passwordInput.type = "password";
          this.classList.remove("fa-eye");
          this.classList.add("fa-eye-slash");
        }
      });
    });
  </script>
      <br>
      <center><button type="submit" class="btn" name="login_user">INICIAR</button></center>
      <br>
    </div>
    <p>
      ¿Todavía no eres miembro? <a href="registro.php" style="font-size: 14px;">REGÍSTRATE</a>
    </p>
  </form>
</div>
<footer>
  <center>V1.0.1 © Todos los derechos reservados. Thunderbike</center>
</footer>
</body>
</html>
