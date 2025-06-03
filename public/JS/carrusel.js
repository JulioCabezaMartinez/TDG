/**
 * Inicializa una nueva instancia de Swiper en el contenedor con clase '.card-wrapper'.
 * Swiper es una biblioteca de carrusel/slider moderna para la web.
 */
window.swiper = new Swiper('.card-wrapper', {
    // Optional parameters
    slidesPerView: 3,
    // centeredSlides: true,
    spaceBetween: 30,
    loop: true,
  
    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
  
    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },

    breakpoints: {
        0: {
            slidesPerView: 1,
            spaceBetween: 10
        },
        640: {
            slidesPerView: 2,
            spaceBetween: 20
        },
        768: {
            slidesPerView: 3,
            spaceBetween: 30
        },
    }
  });