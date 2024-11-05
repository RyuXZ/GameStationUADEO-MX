// Desplazamiento suave a las secciones del perfil
document.querySelectorAll('.profile-nav a').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const targetId = this.getAttribute('href').substring(1);
      document.getElementById(targetId).scrollIntoView({
          behavior: 'smooth'
      });
  });
});

// Manejar la eliminación de la foto de perfil
const deletePhotoBtn = document.getElementById('deletePhotoBtn');
if (deletePhotoBtn) {
  deletePhotoBtn.addEventListener('click', function () {
      if (confirm('¿Estás seguro de que deseas eliminar tu foto de perfil?')) {
          window.location.href = 'delete_profile_photo.php'; // Redirigir al backend para eliminar la foto
      }
  });
}

// Mostrar mensajes de éxito al actualizar la foto de perfil o eliminarla
const urlParams = new URLSearchParams(window.location.search);

// Mostrar mensaje de éxito al cambiar la foto
if (urlParams.get('success')) {
  alert('¡Foto de perfil actualizada exitosamente!');
  const profileImage = document.getElementById('profileImage');
  const timestamp = new Date().getTime(); // Evitar el caché del navegador
  profileImage.src = profileImage.src.split('?')[0] + '?t=' + timestamp;
}

// Mostrar mensaje de éxito al eliminar la foto
if (urlParams.get('deleted')) {
  alert('Foto de perfil eliminada exitosamente.');
  const profileImage = document.getElementById('profileImage');
  profileImage.src = 'Images/users/default-profile.jpg'; // Ruta de la imagen por defecto
}
