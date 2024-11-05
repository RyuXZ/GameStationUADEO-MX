<?php
// Archivo: index.php

// Incluir la configuración de conexión a la base de datos
include 'config.php';

// Iniciar la sesión
session_start();

// Verificar si el usuario está logueado
//definir el ID del usuario logueado
$current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GameStation UADEO Mx - Foro</title>
  <link rel="stylesheet" href="/bootstrap/CSS/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Header -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">GameStation UADEO Mx</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="foro.php">Foro</a>
          </li>
        </ul>
        <?php if ($current_user_id): ?>
          <a href="handle_logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline-light">Iniciar Sesión</a>
          <a href="register.php" class="btn btn-outline-light">Registrarse</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <!-- Sección Principal del Foro -->
  <section class="container mt-5">
    <h2 class="text-center mb-4">Foro de Videojuegos</h2>

    <!-- Lista de Foros -->
    <div class="row">
      <div class="col-md-8">
        <?php
        // Consultar los temas del foro
        $sql_temas = "SELECT id, titulo, descripcion, categoria FROM temas ORDER BY fecha_creacion DESC";
        $result_temas = $conn->query($sql_temas);
        if ($result_temas->num_rows > 0):
          while ($tema = $result_temas->fetch_assoc()):
        ?>
          <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
              <?php echo htmlspecialchars($tema['categoria']); ?>
            </div>
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($tema['titulo']); ?></h5>
              <p class="card-text">Descripción breve del tema... <a href="tema.php?id=<?php echo $tema['id']; ?>">Leer más</a></p>
              <span class="badge bg-primary">Respuestas: <?php // Obtener el número de respuestas de cada tema ?></span>
              <span class="badge bg-success">Me gusta: <?php // Obtener el número de likes de cada tema ?></span>
              <span class="badge bg-info">Comentarios: <?php // Obtener el número de comentarios de cada tema ?></span>
            </div>
          </div>
        <?php
          endwhile;
        else:
        ?>
          <p class="text-center">No hay temas disponibles en el foro.</p>
        <?php endif; ?>
      </div>

      <!-- Sidebar con Categorías Populares -->
      <div class="col-md-4">
        <div class="card">
          <div class="card-header bg-secondary text-white">
            Categorías Populares
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="#">PC Gaming</a></li>
            <li class="list-group-item"><a href="#">Consolas</a></li>
            <li class="list-group-item"><a href="#">Realidad Virtual (VR)</a></li>
            <li class="list-group-item"><a href="#">Off-topic</a></li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-primary text-white text-center py-3 mt-5">
    &copy; 2024 GameStation UADEO Mx. Todos los derechos reservados.
  </footer>

  <!-- Scripts -->
  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
