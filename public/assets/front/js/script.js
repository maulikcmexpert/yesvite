$(".simple").owlCarousel({
    loop: true,
    margin: 5,
    items: 3.5,
    nav: false,
    // rtl:true,
    dots: false,
    autoplay: false,
    navText: false,
    autoplayHoverPause: true,
    autoplaySpeed: 500,
    responsive: {
        320: {
            items: 2.5,
        },
        430: {
            items: 2.5,
        },
        575: {
            items: 3.5,
        },
        600: {
            items: 3.5,
        },
    },
});

//Init the carousel
$("#owl-demo").owlCarousel({
    items: 1,
    loop: true,
    autoplay: true,
    nav: false,
    dots: true,
    padding: 0,
    margin: 0,
    autoplayTimeout: 10000,
    onInitialized: startProgressBar,
    onTranslate: resetProgressBar,
    onTranslated: startProgressBar,
});

function startProgressBar() {
    // apply keyframe animation
    $(".slide-progress").css({
        width: "100%",
        transition: "width 10000ms",
    });
}

function resetProgressBar() {
    $(".slide-progress").css({
        width: 0,
        transition: "width 0s",
    });
}
//Init progressBar where elem is $("#owl-demo")
function progressBar(elem) {
    $elem = elem;
    //build progress bar elements
    buildProgressBar();
    //start counting
    start();
}

// rsvp-slider
$(".owl-carousel").owlCarousel({
    loop: false,
    margin: 10,
    nav: true,
    navText: [
        `<svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8.49984 16.9201L1.97984 10.4001C1.20984 9.63008 1.20984 8.37008 1.97984 7.60008L8.49984 1.08008" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
`,
        `<svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M1.41016 16.9201L7.93016 10.4001C8.70016 9.63008 8.70016 8.37008 7.93016 7.60008L1.41016 1.08008" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>`,
    ],
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 1,
        },
        1000: {
            items: 1,
        },
    },
});

$(".rsvp-slide").owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    navText: [
        `<svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8.49984 16.9201L1.97984 10.4001C1.20984 9.63008 1.20984 8.37008 1.97984 7.60008L8.49984 1.08008" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
`,
        `<svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M1.41016 16.9201L7.93016 10.4001C8.70016 9.63008 8.70016 8.37008 7.93016 7.60008L1.41016 1.08008" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>`,
    ],
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 1,
        },
        1000: {
            items: 1,
        },
    },
});
