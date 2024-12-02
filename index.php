<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ThunderBike Inicio</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" type="thundrbikes.png" href="img/thunderbikes.png">
  <style>
    .password-toggle {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }
    header {
  display: flex;
  align-items: center;
  justify-content: center; /* Centrar horizontalmente */
  width: 100%; /* Cambiar el ancho al 100% */
  margin: 50px auto 0px;
  background-color: transparent; /* Eliminar el color de fondo */
}

h1 {
  color: #ffd700; /* Color del texto dorado */
  margin-left: 30px; /* Ajustar el margen izquierdo si es necesario */
}


    header img {
      margin-right: 50px; /* Espacio entre el logo y el texto */
    }
  
  #background-video {
  position: fixed;
  right: 0;
  bottom: 0;
  min-width: 100%;
  min-height: 100%;
  width: auto;
  height: auto;
  z-index: -1;
  background-size: cover;
}
.container {
  display: flex;
  justify-content: flex-end;
  align-items: flex-start;
  margin-top: 5px; /* Ajusta el margen superior según sea necesario */
}

.btn {
  display: inline-block;
  font-weight: 400;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  user-select: none;
  border: 1px solid transparent;
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  line-height: 1.5;
  border-radius: 0.25rem;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btn-primary {
  color: #000000;
  background-color: #ffd700;
  border-color: #ffd700;
}

.btn-primary:hover {
  color: #ffffff;
  background-color: #cca300;
  border-color: #cca300;
}

.about-us {
        max-width: 1500px; /* Ajustar el ancho máximo del contenedor */
        margin: 0 auto; /* Centrar el contenedor horizontalmente */
        padding: 40px; /* Añadir un espacio interno alrededor del contenido */
        font-size: 18px; /* Reducir el tamaño de fuente general */
        line-height: 1.6; /* Ajustar el espaciado entre líneas */
    }

    .about-us h2 {
        text-align: center; /* Centrar el título */
    }

    .about-us p {
        margin-bottom: 30px; /* Reducir el espacio inferior entre párrafos */
    }

  </style>
</head>
<body>
  <div class="container">
    <a href="logeo.php" class="btn btn-primary">Iniciar Sesión</a>
    </div>
<video id="background-video" autoplay muted loop>
  <source src="img/bicicletas.mp4" type="video/mp4">
</video>
  <header>
  <img src="img/thunderbikes.png" alt="thunderbike" style="width: 100px; height: 100px;">
    <h1>THUNDERBIKE</h1>
  </header>
  <main>
    <!-- Botón de inicio de sesión -->
    <!-- Carrusel -->
    <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="img/tx.png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="img/bici.png" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="img/terreno.png" class="d-block w-100" alt="...">
        </div>
      </div>
    </div>

    <section class="about-us">
      <h2><center>Sobre nosotros</center></h2>
      <p>Bienvenido a nuestra tienda de bicicletas, donde la pasión por el ciclismo se fusiona con el compromiso de ofrecerte la mejor experiencia sobre dos ruedas. Nos enorgullece ser mucho más que un simple punto de venta;
         somos un destino para ciclistas de todos los niveles que buscan no solo productos de calidad, sino también un equipo apasionado que comparte su entusiasmo por el ciclismo.</p>
      <p>En nuestro establecimiento, cada visita es una oportunidad para sumergirse en el mundo emocionante de las bicicletas. Desde los principiantes hasta los ciclistas experimentados, todos son recibidos con la misma calidez y dedicación.
       Nuestro equipo está compuesto por verdaderos entusiastas del ciclismo, personas que viven y respiran la cultura ciclista y que están aquí no solo como vendedores, sino como compañeros de viaje en tu emocionante travesía ciclista.</p>
      <p>Nuestra misión es simple pero poderosa: proporcionarte no solo productos de primera calidad, sino también una experiencia de compra excepcional. Nos esforzamos por ser más que una tienda; somos una comunidad donde los ciclistas pueden reunirse,
      compartir experiencias y obtener el conocimiento y el apoyo que necesitan para llevar su pasión al siguiente nivel.</p>
      <p>Desde bicicletas de montaña diseñadas para conquistar senderos escarpados hasta bicicletas de carretera aerodinámicas para desafiar el asfalto, nuestro amplio catálogo abarca una variedad de estilos, marcas y modelos para satisfacer
      las necesidades y deseos de cada ciclista. Además de bicicletas, también ofrecemos una amplia gama de accesorios y equipo, desde cascos y luces hasta ropa técnica y herramientas de mantenimiento, todo cuidadosamente seleccionado para mejorar tu experiencia en cada viaje.</p>


    </section>
    <br><br>

    <section class="redes-sociales">
      <h2><center>Contáctanos en redes sociales</center></h2>
      <ul>
        <center>
        <img src="img/facebook.png" alt="" style="width: 50px; height: 50px;">
        <li><a href="https://www.facebook.com/" target="_blank">Facebook</a></li>
        <img src="img/Twitter.png" alt="" style="width: 50px; height: 50px;">
        <li><a href="https://twitter.com/" target="_blank">Twitter</a></li>
        <img src="img/instagram.png" alt="" style="width: 50px; height: 50px;">
        <li><a href="https://www.instagram.com/" target="_blank">Instagram</a></li>
        </center>
      </ul>
    </section>
  </main>
  
  <footer>
    <center>V1.0.1 © Todos los derechos reservados. Thunderbike</center>
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Font Awesome -->
</body>
</html>