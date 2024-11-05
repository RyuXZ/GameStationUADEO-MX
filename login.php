<?php
// login.php
session_start();

// Código para manejar el inicio de sesión de usuarios
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gamestationuadeo";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario de inicio de sesión
    $nombre_usuario = isset($_POST['username']) && !empty($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : null;

    // Validar datos recibidos
    if (empty($nombre_usuario) || empty($password)) {
        echo "<p style='color: red;'>Por favor, completa todos los campos.</p>";
    } else {
        // Buscar al usuario en la base de datos
        $sql_check_user = "SELECT * FROM Usuarios WHERE username = ?";
        $stmt = $conn->prepare($sql_check_user);
        $stmt->bind_param("s", $nombre_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verificar la contraseña
            if (password_verify($password, $user['password'])) {
              echo "<p style='color: green;'>Inicio de sesión exitoso. Bienvenido, $nombre_usuario.</p>";
              $_SESSION['username'] = $nombre_usuario; // Guardar el usuario en la sesión
              header("Location: index.php"); // Redirigir a index.php
              exit;
          } else {
              echo "<p style='color: red;'>Contraseña incorrecta.</p>";
          }
          
        } else {
            echo "<p style='color: red;'>Usuario no encontrado.</p>";
        }

        $stmt->close();
    }
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <link rel="shortcut icon" href="/Images/icons/control_icono.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UadeO eSports Community - Iniciar Sesión</title>
  <link rel="stylesheet" href="bootstrap/CSS/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="login-card-container">
    <div class="login-card">
      <section class="container mt-5">
        <h2 class="text-center mb-4">Iniciar Sesión</h2>
        <?php
        if (isset($error_message)) {
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error_message) . '</div>';
        }
        ?>
        <form action="login.php" method="post">
          <div class="mb-3">
            <label for="username" class="form-label">Usuario:</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>
        <div class="mt-3 text-center">
          <a href="forgot_username.html" class="text-muted">¿Olvidaste tu nombre de usuario?</a><br>
          <a href="forgot_password.html" class="text-muted">¿Olvidaste tu contraseña?</a>
        </div>
        <div class="mt-3 text-center">
          <a href="register.html" class="text-muted">¿No tienes una cuenta? Regístrate</a>
        </div>
      </section>
    </div>
  </div>
</body>

</html>
