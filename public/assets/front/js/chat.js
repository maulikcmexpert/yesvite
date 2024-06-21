$("#choose-file").on("change", function () {
    var reader = new FileReader();
    reader.onload = function (e) {
        $("#profileIm").replaceWith(
            `  <img id="profileIm" src="${e.target.result}" alt="user-img">`
        );
    };
    reader.readAsDataURL(this.files[0]);
});
