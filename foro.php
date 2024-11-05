<?php include 'config.php'; ?>
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
        <?php
        session_start();
        // Debug log: checking session status
        error_log("Verificando el estado de la sesión");
        // Mostrar el nombre de usuario y el botón de cerrar sesión si el usuario está autenticado
        if (isset($_SESSION['username'])) {
          error_log("El usuario ha iniciado sesión como: " . $_SESSION['username']);
          echo '<a href="perfil.php" class="nav-link text-white">' . htmlspecialchars($_SESSION['username']) . '</a>';
          echo '<a href="logout.php" class="btn btn-outline-light ms-2">Cerrar Sesión</a>';
        } else {
          // Mostrar botones de inicio de sesión y registro si el usuario no está autenticado
          error_log("El usuario no ha iniciado sesión");
          echo '<a href="login.php" class="btn btn-outline-light">Iniciar Sesión</a>';
          echo '<a href="register.php" class="btn btn-outline-light">Registrarse</a>';
        }
        ?>
      </div>
    </div>
  </nav>

  <!-- Sección Principal del Foro -->
  <section class="container mt-5">
    <h2 class="text-center mb-4">Foro de Videojuegos</h2>

    <!-- Crear un Post -->
    <div class="card mb-4">
      <div class="card-header bg-primary text-white">
        Crear un Post
      </div>
      <div class="card-body">
        <?php
        // Debug log: Checking if user is authenticated for creating a post
        error_log("Verificando si el usuario está autenticado para crear un post");
        // Mostrar el formulario de creación de post solo si el usuario está autenticado
        if (isset($_SESSION['username'])) {
          error_log("El usuario está autenticado, mostrando el formulario de creación de post");
        ?>
        <form action="crear_post.php" method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="titulo" class="form-label">Título:</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
          </div>
          <div class="mb-3">
            <label for="etiquetas" class="form-label">Etiquetas / Palabras Clave:</label>
            <input type="text" class="form-control" id="etiquetas" name="etiquetas" placeholder="Ingrese etiquetas separadas por comas" required>
          </div>
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción / Contexto del Post:</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="imagen" class="form-label">Subir Imagen:</label>
            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
          </div>
          <button type="submit" class="btn btn-success">Publicar</button>
        </form>
        <?php
        } else {
          // Mensaje para indicar que el usuario debe iniciar sesión para crear un post
          error_log("El usuario no está autenticado, mostrando solicitud de inicio de sesión");
          echo '<p>Por favor <a href="login.php">inicia sesión</a> para crear un post.</p>';
        }
        ?>
      </div>
    </div>

    <!-- Lista de Foros -->
    <div class="row">
      <div class="col-md-8">
        <?php
        // Obtener la página actual o establecerla en 1 por defecto
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        error_log("Página actual: " . $pagina);
        $posts_por_pagina = 10;
        $offset = ($pagina - 1) * $posts_por_pagina;
        error_log("Posts por página: " . $posts_por_pagina . ", Offset: " . $offset);

        // Consulta para obtener los temas del foro desde la base de datos, con paginación
        $sql = "SELECT id, categoria, titulo, descripcion, respuestas, me_gusta, comentarios, user_id FROM temas ORDER BY fecha_creacion DESC LIMIT $posts_por_pagina OFFSET $offset";
        error_log("Ejecutando consulta SQL: " . $sql);
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          // Recorrer cada fila de resultados y mostrar los temas del foro
          while ($row = $result->fetch_assoc()) {
            error_log("Mostrando post con ID: " . $row['id']);
            echo '<div class="card mb-4">';
            echo '<div class="card-header bg-secondary text-white">' . htmlspecialchars($row["categoria"]) . '</div>';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($row["titulo"]) . '</h5>';

            // Consulta para obtener la imagen asociada al post
            $sql_image = "SELECT ruta FROM imagenes WHERE post_id = " . $row['id'];
            error_log("Ejecutando consulta SQL para imagen: " . $sql_image);
            $result_image = $conn->query($sql_image);
            if ($result_image->num_rows > 0) {
              // Mostrar la imagen asociada al post si existe
              $row_image = $result_image->fetch_assoc();
              error_log("Mostrando imagen para el post con ID: " . $row['id']);
              echo '<img src="' . htmlspecialchars($row_image["ruta"]) . '" class="img-fluid mb-3" alt="Imagen del post">';
            }

            // Mostrar el contenido del post con un enlace para leer más
            echo '<p class="card-text">' . htmlspecialchars($row["descripcion"]) . '... <a href="tema.php?id=' . htmlspecialchars($row["id"]) . '">Leer más</a></p>';
            echo '<span class="badge bg-primary">Respuestas: ' . htmlspecialchars($row["respuestas"]) . '</span> ';
            echo '<span class="badge bg-success">Me gusta: ' . htmlspecialchars($row["me_gusta"]) . '</span> ';
            echo '<span class="badge bg-info">Comentarios: ' . htmlspecialchars($row["comentarios"]) . '</span> ';
            
            // Mostrar botones de editar/eliminar si el usuario es el autor del post
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
              error_log("El usuario es el autor del post con ID: " . $row['id'] . ", mostrando opciones de editar/eliminar");
              echo '<div class="mt-3">';
              echo '<a href="editar_post.php?id=' . htmlspecialchars($row["id"]) . '" class="btn btn-warning">Editar</a> ';
              echo '<a href="eliminar_post.php?id=' . htmlspecialchars($row["id"]) . '" class="btn btn-danger" onclick="return confirm(\'Estás seguro de que deseas eliminar este post?\')">Eliminar</a>';
              echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
          }
        } else {
          // Mostrar mensaje si no hay temas disponibles
          error_log("No hay posts disponibles para mostrar");
          echo "<p>No hay temas disponibles en este momento.</p>";
        }

        // Consulta para contar el total de posts y calcular la cantidad de páginas
        $sql_total_posts = "SELECT COUNT(*) as total FROM temas";
        error_log("Ejecutando consulta SQL para contar el total de posts: " . $sql_total_posts);
        $result_total_posts = $conn->query($sql_total_posts);
        $total_posts = $result_total_posts->fetch_assoc()['total'];
        error_log("Total de posts: " . $total_posts);
        $total_paginas = ceil($total_posts / $posts_por_pagina);
        error_log("Total de páginas: " . $total_paginas);
        ?>

        <!-- Paginación -->
        <nav aria-label="Paginación de posts">
          <ul class="pagination justify-content-center">
            <?php
            // Generar los enlaces de paginación
            for ($i = 1; $i <= $total_paginas; $i++) {
              error_log("Generando enlace de paginación para la página: " . $i);
              echo '<li class="page-item ' . ($pagina == $i ? 'active' : '') . '"><a class="page-link" href="foro.php?pagina=' . $i . '">' . $i . '</a></li>';
            }
            ?>
          </ul>
        </nav>
      </div>

      <!-- Sidebar con Posts Recientes Publicados y Comentados -->
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-header bg-secondary text-white">
            Posts Recientes Publicados
          </div>
          <ul class="list-group list-group-flush">
            <?php
            // Consulta para obtener los 10 posts publicados recientemente
            $sql_recent_posts = "SELECT id, titulo FROM temas ORDER BY fecha_creacion DESC LIMIT 10";
            error_log("Ejecutando consulta SQL para posts recientes: " . $sql_recent_posts);
            $result_recent_posts = $conn->query($sql_recent_posts);

            if ($result_recent_posts->num_rows > 0) {
              while ($row_recent_post = $result_recent_posts->fetch_assoc()) {
                error_log("Mostrando post reciente con ID: " . $row_recent_post['id']);
                echo '<li class="list-group-item"><a href="tema.php?id=' . htmlspecialchars($row_recent_post["id"]) . '">' . htmlspecialchars($row_recent_post["titulo"]) . '</a></li>';
              }
            } else {
              error_log("No hay posts recientes disponibles");
              echo '<li class="list-group-item">No hay posts publicados recientemente.</li>';
            }
            ?>
          </ul>
        </div>

        <div class="card">
          <div class="card-header bg-secondary text-white">
            Posts Recientes Comentados
          </div>
          <ul class="list-group list-group-flush">
            <?php
            // Consulta para obtener los posts con comentarios recientes
            $sql_recent_comments = "SELECT DISTINCT temas.id, temas.titulo FROM temas INNER JOIN comentarios ON temas.id = comentarios.tema_id ORDER BY comentarios.fecha DESC LIMIT 10";
            error_log("Ejecutando consulta SQL para comentarios recientes: " . $sql_recent_comments);
            $result_recent_comments = $conn->query($sql_recent_comments);

            if ($result_recent_comments->num_rows > 0) {
              while ($row_recent = $result_recent_comments->fetch_assoc()) {
                error_log("Mostrando post con comentarios recientes, ID: " . $row_recent['id']);
                echo '<li class="list-group-item"><a href="tema.php?id=' . htmlspecialchars($row_recent["id"]) . '">' . htmlspecialchars($row_recent["titulo"]) . '</a></li>';
              }
            } else {
              error_log("No hay comentarios recientes disponibles");
              echo '<li class="list-group-item">No hay posts comentados recientemente.</li>';
            }
            ?>
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
