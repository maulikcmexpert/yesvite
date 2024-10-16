// alert();
//  ===== focusinput =====
var base_url = $("#base_url").val();

$(document).on("click", "#ChangeToggle", function () {
    $("#navbar-hamburger").toggleClass("hidden");
    $("#navbar-close").toggleClass("hidden");
});

$(document).on("click", ".businessRegister", function () {
    $("#account_type").val("1");
});

$(document).on("click", ".userRegister", function () {
    $("#account_type").val("0");
});
$(".form-control").on("focusin", function () {
    $(this).next().addClass("floatingfocus");
});

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

// $(".form-control").on("focusout", function () {
//     var text_val = $(this).val();
//     if (text_val === "") {
//         $(this).next().removeClass("floatingfocus");
//     } else {
//         $(this).next().addClass("floatingfocus");
//     }
// });
$(".form-control").on("focusout change keyup", function () {
    var text_val = $(this).val();
    if (text_val === "") {
        $(this).next().removeClass("floatingfocus");
    } else {
        $(this).next().addClass("floatingfocus");
    }
});

$(".form-control").each(function () {
    var text = $(this).val();
    if (text === "") {
        $(this).next().removeClass("floatingfocus");
    } else {
        $(this).next().addClass("floatingfocus");
    }
});

$('html').mouseover(function() {
    $(".form-control").each(function () {
        var text = $(this).val();
        if (text === "") {
            $(this).next().removeClass("floatingfocus");
        } else {
            $(this).next().addClass("floatingfocus");
        }
    });
});

// ========= show-password ===========
// $(document).on('click','.toggle-password',function () {
//     // alert();
//     $(this).toggleClass("fa-eye-slash fa-eye");
//     var input = $(this).prev().prev();
//     if (input.attr("type") == "password") {
//         input.attr("type", "text");
//     } else {
//         input.attr("type", "password");
//     }
// });


$(".toggle-password").click(function () {
    $(this).toggleClass("fa-eye-slash fa-eye");
    var input = $(this).prev().prev();
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});

function loaderHandle(querySelectorId, btnName) {
    var loaderbtn = document.querySelector(querySelectorId);

    loaderbtn.innerHTML = btnName;
    loaderbtn.classList.add("spinning");

    setTimeout(function () {
        loaderbtn.classList.remove("spinning");
        loaderbtn.innerHTML = "Save Changes";
    }, 6000);
}

function removeLoaderHandle(querySelectorId, btnName) {
    var loaderbtn = document.querySelector(querySelectorId);
    loaderbtn.classList.remove("spinning");
    loaderbtn.innerHTML = btnName;
}

var buttonPlus = $(".qty-btn-plus");
var buttonMinus = $(".qty-btn-minus");

var incrementPlus = buttonPlus.click(function () {
    var $n = $(this).parent(".qty-container").find(".input-qty");
    $n.val(Number($n.val()) + 1);
});

var incrementMinus = buttonMinus.click(function () {
    var $n = $(this).parent(".qty-container").find(".input-qty");
    var amount = Number($n.val());
    if (amount > 0) {
        $n.val(amount - 1);
    }
});

const chooseFile = document.getElementById("choose-file");
const bgChooseFile = document.getElementById("bg-choose-file");

const imgPreview = document.getElementById("cover-img");
const bgPreview = document.getElementById("bg-cover-img");
if (chooseFile) {
    chooseFile.addEventListener("change", function () {
        // getImgData();
    });
}
if (bgChooseFile) {
    bgChooseFile.addEventListener("change", function () {
        // getbgImgData();
    });
}
function getImgData() {
    const files = chooseFile.files[0];
    if (files) {
        const fileReader = new FileReader();
        fileReader.readAsDataURL(files);
        fileReader.addEventListener("load", function () {
            imgPreview.style.display = "block";
            imgPreview.innerHTML =
                '<img src="' + this.result + '" id="profileIm"/>';
        });
    }
}

function getbgImgData() {
    const files = bgChooseFile.files[0];
    if (files) {
        const fileReader = new FileReader();
        fileReader.readAsDataURL(files);
        fileReader.addEventListener("load", function () {
            bgPreview.style.display = "block";
            bgPreview.innerHTML = '<img src="' + this.result + '" id="bgIm" />';
        });
    }
}

$(".phone_number").intlTelInput({
    initialCountry: "US",
    separateDialCode: true,
    // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
});

// $("[name=phone_number]").on("blur", function () {
$("[name=countryCode]").on("blur", function () {
    var instance = $("[name=countryCode]");

    var phoneNumber = instance.intlTelInput("getSelectedCountryData").dialCode;
    $("#country_code").val(phoneNumber);
});

$(function () {
    $("#ChangeToggle").click(function () {
        $("#navbar-hamburger").toggleClass("hidden");
        $("#navbar-close").toggleClass("hidden");
    });
});

$('label[for="email"]').addClass("floatingfocus");
$('label[for="password"]').addClass("floatingfocus");

$(document).ready(function () {
    $(".close_advertise").on("click", function (e) {
        e.preventDefault();
        $(".google-add").hide();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            url: base_url+"advertisement_status",
            method: "POST",
            data: {
                closed: true,
            },
            success: function (response) {
                // console.log(response);
            },
        });
    });
});
