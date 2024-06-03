var buttonPlus  = $(".qty-btn-plus");
var buttonMinus = $(".qty-btn-minus");

var incrementPlus = buttonPlus.click(function() {
  var $n = $(this)
  .parent(".qty-container")
  .find(".input-qty");
  $n.val(Number($n.val())+1 );
});

var incrementMinus = buttonMinus.click(function() {
  var $n = $(this)
  .parent(".qty-container")
  .find(".input-qty");
  var amount = Number($n.val());
  if (amount > 0) {
    $n.val(amount-1);
  }
});



jQuery(($) => {
    $('.attachment input[type="file"]')
        .on('change', (event) => {
        let el = $(event.target).closest('.attachment').find('.btn-file');
        
        el
        .find('.btn-file__actions__item')
        el
        .find('.btn-file__preview')
        .css({
            'background-image': 'url(' + window.URL.createObjectURL(event.target.files[0]) + ')'
        });
    });
    });


const chooseFile = document.getElementById("choose-file");
    const imgPreview = document.getElementById("cover-img");

    chooseFile.addEventListener("change", function () {
    getImgData();
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
    

// ========= show-password ===========
document.querySelector('.toggle')
.addEventListener('click', function () {
    this.classList.toggle('activate');
});

$(".toggle-password").click(function () {
$(this).toggleClass("fa-eye-slash fa-eye");
var input = $($(this).attr("toggle"));
if (input.attr("type") == "password") {
    input.attr("type", "text");
} else {
    input.attr("type", "password");
}
});
   

    