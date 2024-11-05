// Carrusel de noticias
document.addEventListener('DOMContentLoaded', function() {
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-images img');
    const totalSlides = slides.length;
  
    document.querySelector('.next-btn').addEventListener('click', function() {
      currentSlide = (currentSlide + 1) % totalSlides;
      updateCarousel();
    });
  
    document.querySelector('.prev-btn').addEventListener('click', function() {
      currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
      updateCarousel();
    });
  
    function updateCarousel() {
      const carouselWidth = document.querySelector('.carousel').offsetWidth;
      const newTransform = -currentSlide * carouselWidth;
      document.querySelector('.carousel-images').style.transform = `translateX(${newTransform}px)`;
    }
});
