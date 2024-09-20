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

// $(".form-control").on("focusout", function () {
//     var text_val = $(this).val();
//     if (text_val === "") {
//         $(this).next().removeClass("floatingfocus");
//     } else {
//         $(this).next().addClass("floatingfocus");
//     }
// });
$(".form-control").on("focusout change", function () {
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

// ========= show-password ===========
$(document).on('click','.toggle-password',function () {
    // alert();
    $(this).toggleClass("fa-eye-slash fa-eye");
    var input = $(this).prev().prev();
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});

toastr.options = {
    closeButton: true,
    newestOnTop: false,
    progressBar: true,
    positionClass: "toast-top-right",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
};

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
