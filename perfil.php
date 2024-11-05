<?php
// Archivo: perfil.php

// Incluir la configuración de conexión a la base de datos
include 'config.php';

// Iniciar la sesión para obtener la información del usuario
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    error_log('Usuario no logueado, redirigiendo a login.php'); // Log para depurar si el usuario no está logueado
    header('Location: login.php'); // Redirigir al login si no está logueado
    exit();
}

// Obtener el ID del usuario logueado
$current_user_id = $_SESSION['user_id'];
error_log('Usuario logueado con ID: ' . $current_user_id); // Log para verificar el ID del usuario logueado

// Obtener el nombre de usuario desde la URL
if (isset($_GET['user'])) {
    $username = $_GET['user'];
    // Consultar el ID del usuario basándose en el nombre de usuario
    $sql_user = "SELECT id FROM usuarios WHERE username = ?";
    $stmt_user = $conn->prepare($sql_user);
    if (!$stmt_user) {
        error_log('Error al preparar la consulta SQL para obtener el ID del usuario: ' . $conn->error);
    }
    $stmt_user->bind_param('s', $username);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    if ($result_user->num_rows > 0) {
        $user_data = $result_user->fetch_assoc();
        $user_id = $user_data['id'];
        error_log('Usuario encontrado: ' . $username . ' con ID: ' . $user_id); // Log para verificar el usuario encontrado
    } else {
        error_log('Usuario no encontrado: ' . $username); // Log si el usuario no existe
        header('Location: error.php');
        exit();
    }
} else {
    error_log('Nombre de usuario no proporcionado en la URL'); // Log si no se proporciona el nombre de usuario
    header('Location: error.php');
    exit();
}

// Consultar la información del perfil del usuario
$sql = "SELECT username, email, fecha_registro FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log('Error al preparar la consulta SQL para obtener la información del perfil: ' . $conn->error);
}
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
error_log('Información del perfil obtenida para el usuario: ' . $user['username']); // Log para verificar la información del perfil obtenida

// Consultar las publicaciones del usuario
$sql_posts = "SELECT descripcion, fecha_creacion FROM temas WHERE user_id = ? ORDER BY fecha_creacion DESC";
$stmt_posts = $conn->prepare($sql_posts);
if (!$stmt_posts) {
    error_log('Error al preparar la consulta SQL para obtener publicaciones: ' . $conn->error);
}
$stmt_posts->bind_param('i', $user_id);
$stmt_posts->execute();
$result_posts = $stmt_posts->get_result();
$posts = $result_posts->fetch_all(MYSQLI_ASSOC);
error_log('Número de publicaciones obtenidas: ' . count($posts)); // Log para verificar el número de publicaciones obtenidas

// Consultar los amigos del usuario (ejemplo)
$sql_friends = "SELECT username FROM usuarios WHERE id IN (SELECT friend_id FROM amigos WHERE user_id = ?)";
$stmt_friends = $conn->prepare($sql_friends);
if (!$stmt_friends) {
    error_log('Error al preparar la consulta SQL para obtener amigos: ' . $conn->error);
}
$stmt_friends->bind_param('i', $user_id);
$stmt_friends->execute();
$result_friends = $stmt_friends->get_result();
$friends = $result_friends->fetch_all(MYSQLI_ASSOC);
error_log('Número de amigos obtenidos: ' . count($friends)); // Log para verificar el número de amigos obtenidos

// Consultar los likes dados por el usuario (ejemplo)
$sql_likes = "SELECT titulo FROM temas WHERE id IN (SELECT tema_id FROM likes WHERE user_id = ?)";
$stmt_likes = $conn->prepare($sql_likes);
if (!$stmt_likes) {
    error_log('Error al preparar la consulta SQL para obtener likes: ' . $conn->error);
}
$stmt_likes->bind_param('i', $user_id);
$stmt_likes->execute();
$result_likes = $stmt_likes->get_result();
$likes = $result_likes->fetch_all(MYSQLI_ASSOC);
error_log('Número de likes obtenidos: ' . count($likes)); // Log para verificar el número de likes obtenidos

// Verificar si el usuario actual ya envió una solicitud de amistad
$sql_request = "SELECT * FROM solicitudes_amistad WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)";
$stmt_request = $conn->prepare($sql_request);
if (!$stmt_request) {
    error_log('Error al preparar la consulta SQL para verificar solicitud de amistad: ' . $conn->error);
}
$stmt_request->bind_param('iiii', $current_user_id, $user_id, $user_id, $current_user_id);
$stmt_request->execute();
$result_request = $stmt_request->get_result();
$friend_request = $result_request->fetch_assoc();
error_log('Solicitud de amistad encontrada: ' . ($friend_request ? 'Sí' : 'No')); // Log para verificar si existe una solicitud de amistad

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GameStation UADEO Mx - Perfil</title>
  <link rel="stylesheet" href="/bootstrap/CSS/bootstrap.min.css">
  <link rel="stylesheet" href="/CSS/style.css">
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
            <a class="nav-link" href="index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="foro.php">Foro</a>
          </li>
        </ul>
        <a href="logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
      </div>
    </div>
  </nav>

  <!-- Sección de Perfil -->
  <section class="container mt-5">
    <h2 class="text-center">Perfil de <?php echo htmlspecialchars($user['username']); ?></h2>
    <div class="card mb-3">
      <div class="row g-0">
        <div class="col-md-4 text-center">
          <img src="Images/users/perfil-usuario.jpg" class="img-fluid rounded-start" alt="Foto de Perfil" id="profileImage" style="max-width: 200px;">
          <div class="mt-3">
            <?php if ($user_id != $current_user_id): ?>
              <!-- Mostrar botón de solicitud de amistad si no es el perfil del usuario logueado -->
              <button class="btn btn-primary" id="friendRequestBtn">
                <?php echo ($friend_request) ? 'Solicitud enviada' : 'Enviar solicitud de amistad'; ?>
              </button>
              <script>
                const friendRequestBtn = document.getElementById('friendRequestBtn');
                friendRequestBtn.addEventListener('click', function() {
                  if (friendRequestBtn.innerText === 'Enviar solicitud de amistad') {
                    friendRequestBtn.innerText = 'Solicitud enviada';
                  } else if (friendRequestBtn.innerText === 'Solicitud enviada') {
                    friendRequestBtn.innerText = 'Enviar solicitud de amistad';
                  }
                });
                friendRequestBtn.addEventListener('mouseover', function() {
                  if (friendRequestBtn.innerText === 'Solicitud enviada') {
                    friendRequestBtn.innerText = 'Cancelar solicitud';
                  }
                });
                friendRequestBtn.addEventListener('mouseout', function() {
                  if (friendRequestBtn.innerText === 'Cancelar solicitud') {
                    friendRequestBtn.innerText = 'Solicitud enviada';
                  }
                });
              </script>
            <?php else: ?>
              <!-- Mostrar formulario de cambio de foto de perfil si es el perfil del usuario logueado -->
              <form id="uploadForm" enctype="multipart/form-data" method="post" action="upload_profile_photo.php">
                <div class="mb-3">
                  <input type="file" class="form-control" name="profile_photo" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Cambiar Foto</button>
              </form>
              <div class="mt-2">
                <button class="btn btn-danger" id="deletePhotoBtn">Eliminar Foto</button>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($user['username']); ?></h5>
            <p class="card-text">Miembro desde: <?php echo date('F Y', strtotime($user['fecha_registro'])); ?></p>
            <p class="card-text">Correo Electrónico: <?php echo htmlspecialchars($user['email']); ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Navegación del perfil -->
    <ul class="nav nav-tabs mb-3" id="profileTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab" aria-controls="posts" aria-selected="true">Publicaciones</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="friends-tab" data-bs-toggle="tab" data-bs-target="#friends" type="button" role="tab" aria-controls="friends" aria-selected="false">Amigos</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="likes-tab" data-bs-toggle="tab" data-bs-target="#likes" type="button" role="tab" aria-controls="likes" aria-selected="false">Likes</button>
      </li>
    </ul>

    <div class="tab-content" id="profileTabContent">
      <!-- Sección de Publicaciones -->
      <div class="tab-pane fade show active" id="posts" role="tabpanel" aria-labelledby="posts-tab">
        <?php foreach ($posts as $post): ?>
          <div class="card mb-3">
            <div class="card-body">
              <p class="card-text"><?php echo htmlspecialchars($post['descripcion']); ?></p>
              <span class="text-muted">Publicado el <?php echo date('d M Y', strtotime($post['fecha_creacion'])); ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Sección de Amigos -->
      <div class="tab-pane fade" id="friends" role="tabpanel" aria-labelledby="friends-tab">
        <ul class="list-group">
          <?php foreach ($friends as $friend): ?>
            <li class="list-group-item"><?php echo htmlspecialchars($friend['username']); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Sección de Likes -->
      <div class="tab-pane fade" id="likes" role="tabpanel" aria-labelledby="likes-tab">
        <ul class="list-group">
          <?php foreach ($likes as $like): ?>
            <li class="list-group-item">Tema: <?php echo htmlspecialchars($like['titulo']); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

  <footer class="bg-primary text-white text-center py-3 mt-5">
    &copy; 2024 GameStation UADEO Mx. Todos los derechos reservados.
  </footer>

  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
