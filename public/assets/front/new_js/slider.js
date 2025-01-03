// ===hostby-slider===
const path =require('../image')
var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 30,
    pagination: {
          el: '.custom-pagination',
          type: 'custom',
          renderCustom: (swiper, current, total) => `${current} of ${total}`,
      },
  });


//   ===story-slider===
  var swiper = new Swiper(".story-slide-slider", {
    slidesPerView: 7,
    spaceBetween: 0,
  });

//   ===story-slider===
  var swiper = new Swiper(".latest-draf-slider", {
    slidesPerView: 2,
    spaceBetween: 20,
    breakpoints: {
      320: {
        slidesPerView: 1.2,
        spaceBetween: 20,
      },
      576: {
        slidesPerView: 1.5,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      992: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
    },
  });

  $('.rsvp-slide').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    navText: [
      `<img src="${path}/left-arrow.png" alt="Left">`,
      `<img src="${path}/right-arrow.png" alt="Right">`
    ],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:1
        }
    }
  })

// Function to update dots for each Swiper instance
function updateDots(swiper, $dotsContainer) {
    const total = swiper.slides.length; // Total slides
    const current = swiper.realIndex + 1; // Current active slide (1-based index)

    // Generate dot HTML
    let dotsHTML = '';
    for (let i = 1; i <= total; i++) {
        dotsHTML += `<span class="dot ${i === current ? 'active' : ''}" data-slide="${i}"></span>`;
    }
    $dotsContainer.html(dotsHTML); // Update the dots container

    // Add click event for navigation
    $dotsContainer.find('.dot').off('click').on('click', function () {
        const slideIndex = parseInt($(this).data('slide'), 10) - 1; // Convert to zero-based index
        swiper.slideTo(slideIndex);
    });
}
