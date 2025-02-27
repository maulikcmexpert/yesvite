$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const designId = urlParams.get('design_id'); // Get 'design_id' from URL

    if (designId) {
        // Find the element with class 'edit_design_tem' and matching data-id, then trigger click
        $('.edit_design_tem[data-id="' + designId + '"]').trigger('click');
        urlParams.delete('design_id');
        const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
        window.history.replaceState(null, '', newUrl);
    }


    // $('input[type="checkbox"]:not(#Allcat)').prop('checked', true);
    $('input[name="design_subcategory"]').prop('checked', false);
    $('#Allcat').prop('checked', false);
    var visibleItems = $('.all_designs:visible').length;
    $('.total_design_count').text(visibleItems + ' Items');
    $('#Allcat').on('change', function () {
        $('.image-item').show();
        $(".categoryNew").show();
        $(".subcategoryNew").hide();
        $(".image-item-new").hide();
        $("#category_name").hide();
        $("#allchecked").hide();
        if ($(this).is(':checked')) {
            const categoryId = $(this).data('category-id');
            const subcategoryId = $(this).data('subcategory-id');

            $('input[name="design_subcategory"]:not(#Allcat)').prop('checked', true);
            $('.image-item').show();
            var visibleItems = $('.all_designs:visible').length;
            $('.total_design_count').text(visibleItems + ' Items');




        } else {
            $('input[name="design_subcategory"]:not(#Allcat)').prop('checked', false);
            $('.image-item').hide();
            var visibleItems = $('.all_designs:visible').length;
            $('.total_design_count').text(visibleItems + ' Items');




        }


    });
    // $('input[name="design_subcategory_new"]').on('change', function () {
    //     let subcategoryId = $(this).data('subcategory-id');

    //     // If checked, check the corresponding checkbox in design_subcategory
    //     if ($(this).is(':checked')) {
    //         $('input[name="design_subcategory"][data-subcategory-id="' + subcategoryId + '"]').prop('checked', true);
    //         $('#Allcat').prop('checked', true);
    //     } else {
    //         // If unchecked, uncheck the corresponding checkbox in design_subcategory
    //         $('input[name="design_subcategory"][data-subcategory-id="' + subcategoryId + '"]').prop('checked', false);
    //         $('#Allcat').prop('checked', false);
    //     }
    // });
    $(document).on('change', 'input[name="design_subcategory"]:not(#Allcat)', function () {

        $(".image-item").hide(); // Hide all images first

        $('input[name="design_subcategory"]:checked').each(function () {
            const categoryId = $(this).data('category-id');
            const subcategoryId = $(this).data('subcategory-id');

            // Show images matching the selected category and subcategory
            $(`.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`).show();
        });

        var visibleItems = $('.image-item:visible').length;
        $('.total_design_count').text(visibleItems + ' Items');
    });

    $('#resetCategories').on('click', function (e) {
        $(".categoryNew").show();
        $(".subcategoryNew").hide();
        $(".image-item-new").hide();
        $("#category_name").hide();
        $("#allchecked").hide();
        e.preventDefault();
        $("#Allcat").prop("checked", false);
        $('input[name="design_subcategory"]:not(#Allcat)').prop('checked', false);
        $('.image-item').hide();
        var visibleItems = $('.all_designs:visible').length;
        $('.total_design_count').text(visibleItems + ' Items');
    });

    document.querySelectorAll('.collection-menu').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.stopPropagation();
        });
    });

    const $cookiesBox = $('.cookies-track');

    if (!localStorage.getItem('cookiesBoxDismissed')) {
        setTimeout(() => {
            $cookiesBox.addClass('active');
        }, 500);
    }

    $('.close-btn').on('click', function () {
        $cookiesBox.removeClass('active');
        localStorage.setItem('cookiesBoxDismissed', 'true');
    });

    $(document).on('input', '#search_design_category', function () {
        $(".categoryNew").show();
        $(".subcategoryNew").hide();
        $(".image-item-new").hide();
        $("#category_name").hide();
        $("#allchecked").hide();
        var search_value = $(this).val();
        $('#home_loader').css('display', 'flex');
        if (search_value == '') {
            $('input[name="design_subcategory"]').prop('checked', true)
            $("#Allcat").prop("checked", true);
        }
        $.ajax({
            url: base_url + "search_features",
            method: 'GET',
            data: {
                search: search_value
            },
            success: function (response) {

                if (response.view) {
                    $('.list_all_design_catgeory').html('');
                    $('.list_all_design_catgeory').html(response.view);
                    $('#home_loader').css('display', 'none');
                    $('.total_design_count').text(response.count + ' Items')

                } else {
                    $('.list_all_design_catgeory').html('No Design Found');
                    $('.total_design_count').text(response.count + ' Items')
                    $('#home_loader').css('display', 'none');
                }
            },
            error: function (error) {
                toastr.error('Some thing went wrong');
            }
        });
    });


    $(document).on('click', '#design_category', function () {

        const categoryId = $(this).data("category-id");
        const subcategoryId = $(this).data("subcategory-id");

        $(`.categoryChecked_${categoryId}:checked`).each(function () {
            const subcategoryIds = $(this).data("subcategory-id");
            $(`.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryIds}"]`)
                .show();
            $('.subcategoryChecked_' + subcategoryIds).prop('checked', true)
        });


        // $('.subcategory_' + categoryId).prop('checked', true)

        $("#allchecked").attr("data-categoryid", categoryId);
        $("#allchecked").attr("data-subcategoryid", subcategoryId);
        $("#category_name").text(category_name);

        // $(`.image-item-new[data-category-id="${categoryId}"]`).show();
        var visibleItems = $(".all_designs:visible").length;
        $(".total_design_count").text(visibleItems + " Items");
    });
});

$(document).on('click', '#allchecked', function () {
    const categoryId = $(this).attr('data-categoryid');
    const subcategoryId = $(this).attr('data-subcategoryid');
    allCheckFun(categoryId, subcategoryId)
})

function allCheckFun(categoryIds, subcategoryIds) {
    $('input[name="design_subcategory_new"]').prop('checked', false)
    // $('input[name="design_subcategory"]').prop('checked', true)
    $(".categoryNew").show();
    $(".subcategoryNew").hide();
    $(".image-item-new").hide();
    $("#category_name").hide();
    $("#allchecked").hide();
    // $('input[name="design_subcategory"]:not(#Allcat)').prop("checked", true);
    // $("#Allcat").prop('checked', true)
    // $('.image-item').show();
    // var visibleItems = $('.all_designs:visible').length;
    // $('.total_design_count').text(visibleItems + ' Items');


    $('input[name="design_category"]:not(#Allcat):checked').each(
        function () {

            const categoryId = $(this).data("category-id");

            const subcategoryId = $(this).data("subcategory-id");

            // // Show images matching the selected categories and subcategories
            $(`.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`)
            .show();
            var visibleItems = $(".all_designs:visible").length;
            $(".total_design_count").text(visibleItems + " Items");
        }
    );

    // let totalCheckboxes = $('input[name="design_subcategory_new"]:not(#Allcat)').length;

    // let checkedCheckboxes = $(`.subcategory_${categoryIds}:not(#Allcat):checked`).length;

    // if(checkedCheckboxes == 0){
    //     $('.categoryChecked_'+categoryIds).prop('checked',false);
    //     $(`.image-item[data-category-id="${categoryIds}"]`).hide();
    // }

    $(`.subcategoryChecked_${subcategoryIds}:checked`).each(function () {

        $(`.image-item-new[data-category-id="${categoryIds}"][data-subcategory-id="${subcategoryIds}"]`)
            .show();
        $('.subcategoryChecked_' + subcategoryIds).prop('checked', false)
    });

    if ($("#search_design_category").val() == "") {
        return
    }
    $("#search_design_category").val('')
    let search_value = '';
    $.ajax({
        url: base_url + "search_design",
        method: 'GET',
        data: {
            search: search_value
        },
        success: function (response) {

            if (response.view) {
                $('.list_all_design_catgeory').html('');
                $('.list_all_design_catgeory').html(response.view);
                $('#home_loader').css('display', 'none');
                $('.total_design_count').text(response.count + ' Items')

            } else {
                $('.list_all_design_catgeory').html('No Design Found');
                $('.total_design_count').text(response.count + ' Items')
                $('#home_loader').css('display', 'none');
            }
        },
        error: function (error) {
            toastr.error('Some thing went wrong');
        }
    });
}


// $(document).on(
//     "change",
//     'input[name="design_subcategory_new"]:not(#Allcat)',
//     function () {
//         $(".image-item-new").hide();
//         $("#category_name").show();
//         $("#allchecked").show();
//         // If all individual checkboxes are checked, check "All Categories"
//         const totalCheckboxes = $(
//             'input[name="design_subcategory_new"]:not(#Allcat)'
//         ).length;
//         const checkedCheckboxes = $(
//             'input[name="design_subcategory_new"]:not(#Allcat):checked'
//         ).length;



//         // Filter images based on checked categories
//         if (checkedCheckboxes > 0) {
//             $(".image-item").hide(); // Hide all images first
//             $('input[name="design_subcategory_new"]:not(#Allcat):checked').each(
//                 function () {
//                     const categoryId = $(this).data("category-id");
//                     const subcategoryId = $(this).data("subcategory-id");

//                     $(`.image-item-new[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`)
//                         .show();

//                     var visibleItems = $(".all_designs:visible").length;
//                     $(".total_design_count").text(visibleItems + " Items");
//                 }
//             );
//         } else {
//             $(".image-item-new").hide(); // Hide all images if no checkboxes are checked
//             var visibleItems = $(".all_designs:visible").length;
//             $(".total_design_count").text(visibleItems + " Items");
//         }
//     }
// );

$("#resetCategoriesNew").on("click", function (e) {



    e.preventDefault();
    $("#Allcat").prop("checked", false);
    $('input[name="design_subcategory_new"]:not(#Allcat)').prop(
        "checked",
        false
    );
    $(".image-item-new").hide();
    var visibleItems = $(".all_designs:visible").length;
    $(".total_design_count").text(visibleItems + " Items");
});
