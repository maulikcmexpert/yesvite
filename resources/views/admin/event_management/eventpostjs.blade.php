<script>
    $('.owl-carousel1').owlCarousel({
        loop: false,
        margin: 10,
        nav: true,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    })


    // var swiper = new Swiper(".mySwiper", {
    //     spaceBetween: 10,
    //     slidesPerView: 4,
    //     freeMode: true,
    //     watchSlidesProgress: true,
    // });
    // var swiper2 = new Swiper(".mySwiper2", {
    //     spaceBetween: 10,
    //     navigation: {
    //         nextEl: ".swiper-button-next",
    //         prevEl: ".swiper-button-prev",
    //     },
    //     thumbs: {
    //         swiper: swiper,
    //     },
    // });

    /*--------------*/



    // Main/Product image slider for product page
    $('.main-img-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        arrows: true,
        fade: true,
        autoplay: true,
        autoplaySpeed: 4000,
        speed: 300,
        lazyLoad: 'ondemand',
        asNavFor: '.thumb-nav',
        // prevArrow: '<div class="slick-prev"><i class="i-prev"></i><span class="sr-only sr-only-focusable">Previous</span></div>',
        // nextArrow: '<div class="slick-next"><i class="i-next"></i><span class="sr-only sr-only-focusable">Next</span></div>'
    });
</script>