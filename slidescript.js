var swiper = new Swiper(".slider-container", {
  slidesPerView: 1,
  spaceBetween: 60,
  loop: true,
  centerSlide: "true",
  grabCursor: "true",
  fade: "true",
  loopFillGroupWithBlank: true, // Ensure the last slide transitions smoothly to the first
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
    dynamicBullets: true,
  },

  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});
