<?php

// Incluir la configuración de conexión a la base de datos
include 'config.php';

// Inicializar mensaje de estado
$mensaje = "";

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar si las contraseñas coinciden
    if ($password !== $confirm_password) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        // Encriptar la contraseña para almacenarla de forma segura
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Preparar la consulta SQL para insertar un nuevo usuario
        $sql = "INSERT INTO usuarios (tipo_usuario, username, email, password) VALUES (1, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt) {
            // Vincular los parámetros
            $stmt->bind_param('sss', $username, $email, $hashed_password);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                $mensaje = "Registro exitoso. Bienvenido a GameStation UADEO Mx.";
            } else {
                // Error al ejecutar la consulta
                error_log('Error al ejecutar la consulta: ' . ($stmt->error ? $stmt->error : 'Error desconocido'));
                $mensaje = "Error en el registro. Inténtalo de nuevo.";
            }
        } else {
            // Error al preparar la consulta
            error_log('Error al preparar la consulta: ' . ($conn->error ? $conn->error : 'Error desconocido'));
            $mensaje = "Error en el registro. Inténtalo de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GameStation UADEO Mx - Registro</title>
  <link rel="stylesheet" href="../bootstrap/CSS/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">GameStation UADEO Mx</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="index.html">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="foro.html">Foro</a>
          </li>
        </ul>
        <a href="login.php" class="btn btn-outline-light">Iniciar Sesión</a>
        <a href="register.php" class="btn btn-outline-light">Registrarse</a>
      </div>
    </div>
  </nav>

  <section class="container mt-5">
    <h2 class="text-center mb-4">Registro de Usuario</h2>

    <!-- Mensaje de registro -->
    <?php if (!empty($mensaje)): ?>
      <div id="status-message" class="alert alert-info mt-3">
        <?php echo htmlspecialchars($mensaje); ?>
      </div>
    <?php endif; ?>

    <form action="register.php" method="post">
      <div class="mb-3">
        <label for="username" class="form-label">Nombre de Usuario:</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Correo Electrónico:</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Contraseña:</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Registrarse</button>
    </form>
  </section>

  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    // Verificar que las contraseñas coincidan
    document.querySelector('form').addEventListener('submit', function (e) {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      if (password !== confirmPassword) {
        e.preventDefault();
        alert('Las contraseñas no coinciden.');
      }
    });
  </script>

  <footer class="bg-primary text-white text-center py-3 mt-5">
    &copy; 2024 GameStation UADEO Mx. Todos los derechos reservados.
  </footer>
</body>
</html>
