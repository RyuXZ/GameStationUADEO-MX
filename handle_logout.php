<?php
// handle_logout.php
session_start();

// Destruir todas las sesiones activas
session_unset();
session_destroy();

// Redirigir al usuario a la página principal
header("Location: index.php");
exit();
?>
