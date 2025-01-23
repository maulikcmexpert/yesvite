// ===hostby-slider===
const path =""
var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 30,
    pagination: {
          el: '.custom-pagination',
          type: 'custom',
          renderCustom: (swiper, current, total) => `${current} of ${total}`,
      },
  });


// ===photo-detal-slider===
var swiper = new Swiper(".photo-detail-slider", {
    slidesPerView: 1,
    spaceBetween: 30,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });


//   ===story-slider===
//   ===story-slider===
var swiper = new Swiper(".story-slide-slider", {
    slidesPerView: 7,
    spaceBetween: 0,
    breakpoints: {
      320: {
        slidesPerView: 4,
        spaceBetween: 0,
      },
      425: {
        slidesPerView: 5,
        spaceBetween: 0,
      },
      576: {
        slidesPerView: 6,
        spaceBetween: 0,
      },
      768: {
        slidesPerView: 7,
        spaceBetween: 20,
      },
      992: {
        slidesPerView: 7,
        spaceBetween: 20,
      },
      1200: {
        slidesPerView: 6,
        spaceBetween: 20,
      },
      1400: {
        slidesPerView: 7,
        spaceBetween: 0,
      },
    },
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


  var swiper = new Swiper(".posts-card-post", {
    slidesPerView: 1,
    spaceBetween: 30,
    pagination: {
        el: '.custom-pagination',
        type: 'custom',
        renderCustom: (swiper, current, total) => {
            // Render number pagination only inside .custom-pagination
            return `<span>${current} of ${total}</span>`;
        },
    },
    on: {
        init: () => updateDots(), // Update dots on initialization
        slideChange: () => updateDots(), // Update dots on slide change
    },
  });

// Function to update dots for each Swiper instance
function updateDots() {
    const total = swiper.slides.length;
    const current = swiper.realIndex + 1;
    const $dotsContainer = $('.custom-dots-container');

    // Generate dot HTML
    let dotsHTML = '';
    for (let i = 1; i <= total; i++) {
        dotsHTML += `<span class="dot ${i === current ? 'active' : ''}" data-slide="${i}"></span>`;
    }
    $dotsContainer.html(dotsHTML);

    // Add click event listener for dot navigation
    $dotsContainer.find('.dot').on('click', function () {
        const slideIndex = parseInt($(this).data('slide'), 10);
        swiper.slideTo(slideIndex - 1); // Swiper index starts at 0
    });
  }

  updateDots();


  $('.rsvp-slide').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    navText: [
      `<svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8.49984 16.9201L1.97984 10.4001C1.20984 9.63008 1.20984 8.37008 1.97984 7.60008L8.49984 1.08008" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
`,
      `<svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M1.41016 16.9201L7.93016 10.4001C8.70016 9.63008 8.70016 8.37008 7.93016 7.60008L1.41016 1.08008" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>`
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
