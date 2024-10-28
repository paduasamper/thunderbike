<?php
session_start();

// Inicializar variables
$username = "";
$email    = "";
$errors = array(); 

// Conexión a la base de datos
$db = mysqli_connect('localhost', 'root', '', 'thunderbike');

// REGISTRAR USUARIO
if (isset($_POST['reg_user'])) {
  // Recibir todos los valores de entrada del formulario
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
  $role = mysqli_real_escape_string($db, $_POST['role']); // Obtener el rol del formulario

  // Validación del formulario: asegúrese de que el formulario esté correctamente completado...
  // agregando (array_push()) el error correspondiente a la matriz $errors
  if (empty($username)) { array_push($errors, "Se requiere nombre de usuario"); }
  if (empty($email)) { array_push($errors, "Se requiere correo electrónico"); }
  if (empty($password_1)) { array_push($errors, "Se requiere contraseña"); }
  if ($password_1 != $password_2) {
    array_push($errors, "Las dos contraseñas no coinciden");
  }

  // Primero verifique la base de datos para asegurarse si
  // un usuario no existe ya con el mismo nombre de usuario y/o correo electrónico
  $user_check_query = "SELECT * FROM usuarios WHERE nombre='$username' OR correo='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // Si el usuario existe
    if ($user['nombre'] === $username) {
      array_push($errors, "El nombre de usuario ya existe");
    }

    if ($user['correo'] === $email) {
      array_push($errors, "El correo electrónico ya existe");
    }
  }

  // Finalmente, registre al usuario si no hay errores en el formulario.
  if (count($errors) == 0) {
    $password = md5($password_1);// Cifrar la contraseña antes de guardarla en la base de datos

    $query = "INSERT INTO usuarios (nombre, correo, clave, rol) 
          VALUES('$username', '$email', '$password', '$role')"; // Insertar el rol también
    mysqli_query($db, $query);
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role; // Guardar el rol en la sesión
    $_SESSION['success'] = "Ahora está registrado y conectado";
    header('location: index.php');
  }
}

// INICIAR SESIÓN USUARIO
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
      array_push($errors, "Nombre de Usuario requerido");
  }
  if (empty($password)) {
      array_push($errors, "Contraseña es requerida");
  }

  if (count($errors) == 0) {
      $password = md5($password);
      $query = "SELECT * FROM usuarios WHERE nombre='$username' AND clave='$password'";
      $results = mysqli_query($db, $query);
      if (mysqli_num_rows($results) == 1) {
          $_SESSION['username'] = $username;
          // Obtener el rol del usuario y guardarlo en la sesión
          $user = mysqli_fetch_assoc($results);
          $_SESSION['role'] = $user['rol'];

          // Redirigir según el rol del usuario
          switch ($_SESSION['role']) {
              case 'administrador':
                  header('location: inicio.php');
                  break;
              case 'mecanico':
                  header('location: mecanico_dashboard.php');
                  break;
              case 'vendedor':
                  header('location: vendedor_dashboard.php');
                  break;
              default:
                  header('location: inicio.php');
                  break;
          }
          exit();
      } else {
          array_push($errors, "Combinación incorrecta de nombre de usuario/contraseña");
      }
  }
}

?>