//  ===== focusinput =====
$(document).on("click", ".businessRegister", function () {
    $("#account_type").val("1");
});

$(document).on("click", ".userRegister", function () {
    $("#account_type").val("0");
});
$(".form-control").on("focusin", function () {
    $(this).next().addClass("floatingfocus");
});

$(".form-control").on("focusout", function () {
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
$(".toggle-password").click(function () {
    $(this).toggleClass("fa-eye-slash fa-eye");
    var input = $(this).prev().prev();
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});
// Set the options that I want

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

chooseFile.addEventListener("change", function () {
    getImgData();
});

bgChooseFile.addEventListener("change", function () {
    getbgImgData();
});

function getImgData() {
    const files = chooseFile.files[0];
    if (files) {
        const fileReader = new FileReader();
        fileReader.readAsDataURL(files);
        fileReader.addEventListener("load", function () {
            imgPreview.style.display = "block";
            imgPreview.innerHTML = '<img src="' + this.result + '" />';
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
            bgPreview.innerHTML = '<img src="' + this.result + '" />';
        });
    }
}
