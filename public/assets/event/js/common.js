
// ======= increement/deecrement rsvp ======

 
$('#ChangeToggle').click(function() {
    $('#navbar-hamburger').toggleClass('hidden');
    $('#navbar-close').toggleClass('hidden');  
});

var buttonPlus  = $(".qty-btn-plus-qty");
var buttonMinus = $(".qty-btn-minus-qty");

$(document).on('click','.qty-btn-plus-qty',function(){
    var $input = $(this).siblings(".input-qty");
    var value = parseInt($input.val());
    $input.val(value + 1);
});

$(document).on('click','.qty-btn-minus-qty',function(){
    var $input = $(this).siblings(".input-qty");
    var value = parseInt($input.val());
    var self_quantity= parseInt($('#self_bring_qty').val());
    if (value > 0) {
        if(self_quantity >= value){
            $('#self_bring_qty').val(value-1);
        }
        $input.val(value - 1);
    }
});

$(document).on('click','.qty-btn-plus',function(){
    var $input = $(this).siblings(".input-qty");
    var value = parseInt($input.val());
    $input.val(value + 1);
});

$(document).on('click','.qty-btn-minus',function(){
    var $input = $(this).siblings(".input-qty");
    var value = parseInt($input.val());
    if (value > 0) {
        $input.val(value - 1);
    }
});

// var buttonPlus  = $(".qty-btn-plus-qty");
// var buttonMinus = $(".qty-btn-minus-qty");

// $(buttonPlus).click(function () {
//     var $input = $(this).siblings(".input-qty");
//     var value = parseInt($input.val());
//     $input.val(value + 1);
// });

// $(buttonMinus).click(function () {
//     var $input = $(this).siblings(".input-qty");
//     var value = parseInt($input.val());
//     var self_quantity=$('#self_bring_qty').val();
//     if (value > 0) {
//         if(self_quantity>=value){
//             $('#self_bring_qty').val(value-1);
//         }
//         $input.val(value - 1);
//     }
// });

// var incrementPlus = buttonPlus.click(function() {
//   var $n = $(this)
//   .parent(".qty-container")
//   .find(".input-qty");
//   $n.val(Number($n.val())+1 );
// });

// var incrementMinus = buttonMinus.click(function() {
//   var $n = $(this)
//   .parent(".qty-container")
//   .find(".input-qty");
//   var amount = Number($n.val());
//   if (amount > 0) {
//     $n.val(amount-1);
//   }
// });

//  ===== focusinput =====
// $(".form-control").on('focusin', 
//     function(){
//     $(this).next().addClass('floatingfocus');
// })

// $(".form-control").on('focusout', function(){
//     var text_val = $(this).val();
//         if (text_val === "") {
//         $(this).next().removeClass('floatingfocus');
//     } else {
//         $(this).next().addClass('floatingfocus');
//     }
// });

// $(".form-control").each(function() {
//     console.log("text",text);
//     var text = $(this).val();
//     if (text === "") {
//         $(this).next().removeClass('floatingfocus');
//     } else {
//         $(this).next().addClass('floatingfocus');
//     }
// });




$(document).ready(function() {
    // Function to handle floating label behavior based on input value
    function toggleFloatingLabel(input) {
        var text_val = $(input).val();
        if (text_val === "") {
            $(input).next().removeClass('floatingfocus');
        } else {
            $(input).next().addClass('floatingfocus');
        }
    }

    // Handle focus in
    $(".form-control").on('focusin', function() {
        $(this).next().addClass('floatingfocus');
    });

    // Handle focus out and check the value
    $(".form-control").on('focusout', function() {
        toggleFloatingLabel(this);
    });

    // On page load, check each input and apply the floating label if necessary
    $(".form-control").each(function() {
        toggleFloatingLabel(this);
    });
});



// ========= show-password ===========
$(".toggle-password").click(function () {
    $(this).toggleClass("fa-eye-slash fa-eye");
    var input = $(this).prev().prev();
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});


// const chooseFile = document.getElementById("choose-file");
// const imgPreview = document.getElementById("cover-img");

// const bgChooseFile = document.getElementById("bg-choose-file");
// const bgcover = document.getElementById("coverImg");

//     chooseFile.addEventListener("change", function () {
//     getImgData();
//     });

//     bgChooseFile.addEventListener("change", function () {
//         getBGImgData();
//     })

//     function getImgData() {
//     const files = chooseFile.files[0];
//     if (files) {
//         const fileReader = new FileReader();
//         fileReader.readAsDataURL(files);
//         fileReader.addEventListener("load", function () {
//         imgPreview.style.display = "block";
//         imgPreview.innerHTML = '<img src="' + this.result + '" />';
//         });    
//     }
    
//     }

//     function getBGImgData() {
//         const files = bgChooseFile.files[0];
//         if (files) {
//             const fileReader = new FileReader();
//             fileReader.readAsDataURL(files);
//             fileReader.addEventListener("load", function () {
//             bgcover.style.display = "block";
//             bgcover.innerHTML = '<img src="' + this.result + '" />';
//             });    
//         }
        
//     }


document.addEventListener("DOMContentLoaded", function() {
    const chooseFile = document.getElementById("choose-file");
    const imgPreview = document.getElementById("cover-img");

    const bgChooseFile = document.getElementById("bg-choose-file");
    const bgCover = document.getElementById("coverImg");

    if (chooseFile && imgPreview) {
        chooseFile.addEventListener("change", function () {
            getImgData(chooseFile, imgPreview);
        });
    }

    if (bgChooseFile && bgCover) {
        bgChooseFile.addEventListener("change", function () {
            getImgData(bgChooseFile, bgCover);
        });
    }

    function getImgData(fileInput, imgContainer) {
        const files = fileInput.files[0];
        if (files) {
            const fileReader = new FileReader();
            fileReader.readAsDataURL(files);
            fileReader.addEventListener("load", function () {
                imgContainer.style.display = "block";
                imgContainer.innerHTML = '<img src="' + this.result + '" />';
            });
        }
    }
});
    

// ====== loader-btn
$(function(){
    var loaderbtn = document.querySelector('.loaderbtn');
    loaderbtn.addEventListener("click", function() {
        loaderbtn.innerHTML = "Save Changes";
        loaderbtn.classList.add('spinning');
      setTimeout( 
            function  (){  
                loaderbtn.classList.remove('spinning');
                loaderbtn.innerHTML = "Save Changes";
                
            }, 6000);
    }, false);
    
});


$( window ).on( "load", function() {
    console.log( "window loaded" );
    $("#sidebar").css({"right": "-200%", "width": "0px"});
});

// function toggleSidebar(id) {
//     const sidebar = document.getElementById(id);
//     const overlay = document.getElementById(id+'_overlay');

//     if (sidebar.style.right === '0px') {
//         // Hide sidebar
//         sidebar.style.right = '-500px';
//         sidebar.style.width = "0px";
//         overlay.classList.remove('visible');
//     } else {
//         // Show sidebar
//         sidebar.style.right = '0px';
//         sidebar.style.width = "100%"
//         overlay.classList.add('visible');
//     }
// }


// =====submenu js===
// function handalsubmenu() {
//     const submenu = document.querySelector('.dropdown-submenu');
//     submenu.classList.add('active');
// }




// =========== show-hide =========
// $(document).ready(function() {
//     let visibility = false;

//     $('#toggle-button').on('click', function() {
//         visibility = !visibility;
//         if (visibility) {
//             $('#details').slideDown('slow');
//             $('#chevron i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
//         } else {
//             $('#details').slideUp('slow');
//             $('#chevron i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
//         }
//     });
// });
   

$(document).ready(function(){
    $('.toggleButton').on('click', function() {
        const $details = $('.details');
        const $button = $('.toggleButton');
        
        $details.stop(true, true).slideToggle(300, function() {
            const isVisible = $details.is(':visible');
            $button.html(isVisible ? '<div class="w-100 d-flex justify-content-between"><span class="limited-hide">Limited Featres</span><span class="">Hide details <i class="fa-solid fa-chevron-up chevron"></i></span> </div>' : '<div class="w-100 d-flex justify-content-between"> <span class="limited-show">Limited Featres (15 guests max)</span><span class="">Show details <i class="fa-solid fa-chevron-down chevron"></i></span></div>');
            // ▲ for up, ▼ for down
        });
    });
});


$(document).ready(function(){
    $('.toggleButtonpro').on('click', function() {
        const $details = $('.detailspro');
        const $button = $('.toggleButtonpro');
        
        $details.stop(true, true).slideToggle(300, function() {
            const isVisible = $details.is(':visible');
            $button.html(isVisible ? '<div class="w-100 d-flex justify-content-between" style="cursor:pointer"><span class="limited-hide">Pay as you go <strong>per event</strong></span><span class="">Hide details <i class="fa-solid fa-chevron-up chevron"></i></span> </div>' : '<div class="w-100 d-flex justify-content-between" style="cursor:pointer"> <span class="limited-show" style="color:green">All the PRO features for this one event</span><span class="">Show details <i class="fa-solid fa-chevron-down chevron"></i></span></div>');
            // ▲ for up, ▼ for down
        });
    });
});


$(document).ready(function(){
    $('.toggledeal').on('click', function() {
        const $details = $('.detailsdeal');
        const $button = $('.toggledeal');
        
        $details.stop(true, true).slideToggle(300, function() {
            const isVisible = $details.is(':visible');
            $button.html(isVisible ? '<div class="w-100 d-flex justify-content-between" style="cursor:pointer"><span class="limited-hide">Best Deal!</strong></span><span class="">Hide details <i class="fa-solid fa-chevron-up chevron"></i></span> </div>' : '<div class="w-100 d-flex justify-content-between" style="cursor:pointer"> <span class="limited-show" style="color:green">Best Deal!</span><span class="">Show details <i class="fa-solid fa-chevron-down chevron"></i></span></div>');
            // ▲ for up, ▼ for down
        });
    });
});