// ===hostby-slider===
var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 30,
    pagination: {
          el: '.custom-pagination',
          type: 'custom',
          renderCustom: (swiper, current, total) => `${current} of ${total}`,
    },
  });


// //   ===story-slider===
//   var swiper = new Swiper(".story-slide-slider", {
//     slidesPerView: 7,
//     spaceBetween: 0,
//     breakpoints: {
//       320: {
//         slidesPerView: 4,
//         spaceBetween: 0,
//       },
//       425: {
//         slidesPerView: 5,
//         spaceBetween: 0,
//       },
//       576: {
//         slidesPerView: 6,
//         spaceBetween: 0,
//       },
//       768: {
//         slidesPerView: 7,
//         spaceBetween: 20,
//       },
//       992: {
//         slidesPerView: 7,
//         spaceBetween: 20,
//       },
//       1200: {
//         slidesPerView: 6,
//         spaceBetween: 20,
//       },
//       1400: {
//         slidesPerView: 7,
//         spaceBetween: 0,
//       },
//     },
//   });

// //   ===story-slider===
//   var swiper = new Swiper(".latest-draf-slider", {
//     slidesPerView: 2,
//     spaceBetween: 20,
//     breakpoints: {
//       320: {
//         slidesPerView: 1.2,
//         spaceBetween: 20,
//       },
//       576: {
//         slidesPerView: 1.5,
//         spaceBetween: 20,
//       },
//       768: {
//         slidesPerView: 1,
//         spaceBetween: 20,
//       },
//       992: {
//         slidesPerView: 2,
//         spaceBetween: 20,
//       },
//     },
//   });


// var swiper = new Swiper(".posts-card-post", {
//   slidesPerView: 1,
//   spaceBetween: 30,
//   pagination: {
//       el: '.custom-pagination',
//       type: 'custom',
//       renderCustom: (swiper, current, total) => {
//           // Render number pagination only inside .custom-pagination
//           return `<span>${current} of ${total}</span>`;
//       },
//   },
//   on: {
//       init: () => updateDots(), // Update dots on initialization
//       slideChange: () => updateDots(), // Update dots on slide change
//   },
// });

// Function to render custom dots outside .custom-pagination
