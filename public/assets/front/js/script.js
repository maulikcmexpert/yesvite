


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
      430:{
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
  nav:false,
  dots: true,
  padding: 0,
  margin: 0,
  autoplayTimeout:10000,
  onInitialized: startProgressBar,
  onTranslate: resetProgressBar,
  onTranslated: startProgressBar
});

 function startProgressBar() {
  // apply keyframe animation
  $(".slide-progress").css({
    width: "100%",
    transition: "width 10000ms"
  });
}

function resetProgressBar() {
  $(".slide-progress").css({
    width: 0,
    transition: "width 0s"
  });
}
//Init progressBar where elem is $("#owl-demo")
function progressBar(elem){
  $elem = elem;
  //build progress bar elements
  buildProgressBar();
  //start counting
  start();
}

// rsvp-slider
$('.owl-carousel').owlCarousel({
  loop:true,
  margin:10,
  nav:true,
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