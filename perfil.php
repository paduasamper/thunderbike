<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil</title>
  <link rel="icon" type="image/png" href="../img/thunderbikes.png">
  <style>
    body {
      background-color: while;
      font-family: Arial, sans-serif;
    }
    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .card {
      background-color: #f2f2f2;
      padding: 30px;
      border-radius: 10px;
      width: 750px;
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
      text-align: center;
    }
    .profile-image img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
    }
    .profile-name {
      font-size: 1.5em;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .section-title {
      font-size: 1.2em;
      font-weight: bold;
      margin-top: 20px;
    }
    .about {
      text-align: left;
      font-size: 0.9em;
    }
    .details {
      text-align: left;
      font-size: 0.9em;
    }
    .details span {
      font-weight: bold;
    }
    .details p {
      margin: 5px 0;
    }
    .social-icons {
      margin-top: 20px;
    }
    .social-icons a {
      margin: 0 10px;
      text-decoration: none;
      color: #000;
      font-size: 1.5em;
    }
  </style>
</head>
<body>
<a href="inicio.php" id="indexBtn" class="button">Regresar</a>
  <div class="container">
    <div class="card">
      <h2 class="profile-name">Perfil</h2>

      <!-- Imagen de perfil -->
      <div class="profile-image">
        <img src="img/avatar.png" alt="Avatar">
      </div>

      <!-- Acerca de mí -->
      <div class="about">
        <h3 class="section-title">Acerca de mí</h3>
        <p>Soy el gerente de Thunderbike, una empresa líder en la venta de repuestos y reparación de bicicletas.</p>
      </div>

      <!-- Detalles -->
      <div class="details">
        <h3 class="section-title">Detalles</h3>
        <p><span>Nombre:</span> Javier Leon
        <!-- <?php
        // Mostrar el nombre del usuario logueado
        session_start(); 
        echo isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : 'Usuario no identificado';
        ?></p> -->
        <p><span>Edad:</span> 35 años</p>
        <p><span>Ubicación:</span> Thunderbike, España</p>
      </div>

      <!-- Redes sociales -->
      <div class="social-icons">
        <img src="img/facebook.png" alt="" style="width: 30px; height: 30px;">
        <a href="https://facebook.com" target="_blank">Facebook</a>
        <img src="img/Twitter.png" alt="" style="width: 30px; height: 30px;">
        <a href="https://twitter.com" target="_blank">Twitter</a>
        <img src="img/instagram.png" alt="" style="width: 30px; height: 30px;">
        <a href="https://instagram.com" target="_blank">Instagram</a>
      </div>
    </div>
  </div>
</body>
</html>

