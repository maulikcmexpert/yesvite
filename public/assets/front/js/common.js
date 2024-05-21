// jQuery(($) => {
//     $('.attachment input[type="file"]')
//         .on('change', (event) => {
//         let el = $(event.target).closest('.attachment').find('.btn-file');

//         el
//         .find('.btn-file__actions__item')
//         el
//         .find('.btn-file__preview')
//         .css({
//             'background-image': 'url(' + window.URL.createObjectURL(event.target.files[0]) + ')'
//         });
//     });
//     });

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
