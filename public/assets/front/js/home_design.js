$(document).ready(function () {

    var base_url = $("#base_url").val()
    $(document).on("click", ".edit_design_tem", function () {
        var id = $(this).attr('data-id');
        var image = $(this).attr('data-image');
        var category_name = $(this).attr('data-subcategory_name');
        localStorage.setItem("image", image);
        localStorage.setItem("category_name", category_name);
        window.location.href = base_url + "events?design_id=" + id;
    })
    const urlParams = new URLSearchParams(window.location.search);
    const designId = urlParams.get('design_id'); // Get 'design_id' from URL

    if (designId) {
        // Find the element with class 'edit_design_tem' and matching data-id, then trigger click
        $('.edit_design_tem[data-id="' + designId + '"]').trigger('click');
        urlParams.delete('design_id');
        const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
        window.history.replaceState(null, '', newUrl);
    }
    $(".default_show").show();

    $('input[name="design_subcategory"]').prop('checked', false);
    $('#Allcat').prop('checked', false);


    updateTotalCount();

    // $('input[type="checkbox"]:not(#Allcat)').prop('checked', true);

    $('#Allcat').on('change', function () {
        $(".image-item").show(); // Show all default images



        if ($(this).is(':checked')) {
            // Show all default images and hide new images
            $(".default_show").show();
            $('.image-item-new').hide();


            // Hide category name and checkbox container
            $("#category_name").hide();
            $("#allchecked").hide();

            // Check all subcategory checkboxes
            $('input[name="design_subcategory"]').prop('checked', true);
        }

        else {
            // Uncheck all subcategories
            $('input[name="design_subcategory"]').prop('checked', false);

            $(".image-item").removeClass('d-none');
            // Hide all images
            $(".default_show").show();
            $('.image-item-new').hide();
        }

        updateTotalCount();
    });

    // Handle individual subcategory checkbox change
    $(document).on('change', 'input[name="design_subcategory"]:not(#Allcat)', function () {
        $(".image-item").hide(); // Hide all default images
        $(".image-item-new").hide(); // Hide all new images

        let default_s = 0;
        var value = $(this).val();

        if ($(this).is(":checked")) {
            $(".selected-items").append(
                `<span class="selected-item" data-value="${value}">
                ${value} <span class="close-btn">x</span>
            </span>`
            );
        } else {
            $(".selected-items").find(`[data-value='${value}']`).remove();
        }


        $('input[name="design_subcategory"]:checked').each(function () {
            default_s++;
            $(".image-item").removeClass('d-none');
            const categoryId = $(this).data('category-id');
            const subcategoryId = $(this).data('subcategory-id');

            // Show filtered images matching checked categories and subcategories
            $(`.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`).show();

        });

        if (default_s == 0) {
            $(".image-item").removeClass('d-none');
            $(".default_show").show();
        }
        updateTotalCount();
    });
    $(document).on("click", ".close-btn", function () {
        var parent = $(this).parent();
        var value = parent.attr("data-value");

        // Uncheck the corresponding checkbox
        $('input[name="design_subcategory"][value="' + value + '"]').prop("checked", false).trigger("change");

        // Remove the selected item from the list
        parent.remove();
    });
    // Function to update total count of visible items
    function updateTotalCount() {
        var visibleItems = $('.image-item:visible, .image-item-new:visible').length;
        $('.total_design_count').text(visibleItems + ' Items');
    }


    $('#resetCategories').on('click', function (e) {
        e.preventDefault();
        $(".categoryNew").show();
        $(".subcategoryNew").hide();
        $(".image-item-new").hide(); // Hide filtered items
        $(".image-item").show(); // Show default images
        $("#category_name").hide();
        $("#allchecked").hide();
        $("#Allcat").prop("checked", false);
        $('input[name="design_subcategory"]:not(#Allcat)').prop('checked', false);
        $(".selected-items").empty();
        var visibleItems = $('.image-item:visible').length;
        $('.total_design_count').text($('.default_show:visible').length + ' Items');
    });
    $('#filtered_results').hide();
    // $('#search_design_category').on('keyup', function () {
    //     let query = $(this).val().toLowerCase();
    //     $('#filtered_results').show();
    //     let results = '';

    //     if (query.length > 0) {
    //         designData.forEach(category => {
    //             if (category.name.toLowerCase().includes(query)) {
    //                 results +=
    //                     `<div class="search-item category"  data-category-id="${category.id}"  data-name="${category.name}">${category.name}</div>`;
    //             }
    //             // Check if no subcategory matched and add "No Data Found"

    //             category.subcategories.forEach(subcategory => {
    //                 if (subcategory.name.toLowerCase().includes(query)) {
    //                     results +=
    //                         `<div class="search-item subcategory" data-id="${subcategory.id}" data-category-id="${category.id}" data-name="${subcategory.name}">${subcategory.name}</div>`;
    //                 }
    //                 // Check if no subcategory matched and add "No Data Found"


    //             });
    //         });
    //         if (results === '') {
    //             results +=
    //                 `<div class="search-item no-data">No Data Found</div>`;
    //         }
    //         $('#filtered_results').html(results);
    //     } else {
    //         // When search is cleared, restore the default 30 images
    //         $('#filtered_results').html('');
    //         $('#filtered_results').hide();
    //         $('input[name="design_subcategory"]').prop('checked', false);

    //         $('.total_design_count').text($('.default_show:visible').length + ' Items');
    //     }
    // });

    // Click event for search results
    
    $('#search_design_category').on('keyup', function () {
        let query = $(this).val().toLowerCase().trim();
        // $('#filtered_results').show();
        
        let results = '';
        
        if (query.length > 0) {
            $('.image-item').each(function () {
                let tags = $(this).data('tags') ? $(this).data('tags').toLowerCase().split(',') : [];
                
                // Check if any tag matches the query
                if (tags.some(tag => tag.includes(query))) {
                    $(this).show();
                    $(this).removeClass('fadeInDown');
                    $(this).css('visibility','visible');
                    $(this).removeClass('wow');
                    $('.total_design_count').text($('.image-item:visible').length + ' Items');
                    $('#filtered_results').hide();

                } else {
                    $(this).hide();
                }
            });
    
            // Check if no matching items are found
            if ($('.image-item:visible').length === 0) {
                $('.total_design_count').text($('.image-item:visible').length +
                ' Items');
         
                results +=`<div class="search-item no-data">No Data Found</div>`;
                $('#filtered_results').show();
                 $('#filtered_results').html(results);
            }
        } else {
            // Show all items when the search box is cleared
            $('.image-item').addClass('fadeInDown');
            $('.image-item').addClass('wow');
            $('.image-item').show();
            $('#filtered_results').hide();
        }
    
        // $('#filtered_results').html(results);
    });
    
    $(document).on('click', '.search-item', function () {
        let selectedText = $(this).data('name');
        let categoryId = $(this).data('category-id');
        let subcategoryId = $(this).data('id');

        $('#search_design_category').val(selectedText);
        $('#filtered_results').html(''); // Clear search results
        $('#filtered_results').hide();
        $('.image-item').hide();

        if (categoryId && subcategoryId) {
            // Show only images that match category and subcategory
            $(`.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`)
                .show();
        } else if (categoryId) {


            $(`.image-item[data-category-id="${categoryId}"]`).show();
        }

        $(`input[name="design_subcategory"][data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`)
            .prop('checked', true);
        // if ($(this).hasClass('subcategory')) {

        //     let images = designData.find(c => c.id == categoryId)
        //         .subcategories.find(s => s.id == subcategoryId).images;


        //     // Auto-check the corresponding subcategory checkbox
        //
        // }

        $('.total_design_count').text($('.image-item:visible').length + ' Items');
    });

    let previousSearch = "";

    $('#search_design_category').on('input', function () {
        let query = $(this).val().trim();

        if (query === '') {
            $('#filtered_results').html('').addClass('d-none');

            // Show only default images
            $(".default_show").show();

            // Ensure checked subcategories are unchecked
            $('input[name="design_subcategory"]').prop('checked', false);

            // Update the total count of visible images
            updateTotalCount();
        } else {
            $('#filtered_results').removeClass('d-none');

            // Check if the first character of the previous search is different from the current one
            if (previousSearch.length > 0 && previousSearch.charAt(0).toLowerCase() !== query.charAt(0).toLowerCase()) {
                $('input[name="design_subcategory"]').prop('checked', false);
            }
        }

        previousSearch = query; // Store the current search query
    });








    // $(document).on('input', '#search_design_category', function () {
    //     $(".image-item").hide();
    //     $(".image-item-").show();
    //     var search_value = $(this).val();
    //     $('#home_loader').css('display', 'flex');
    //     if (search_value == '') {
    //         $('input[name="design_subcategory"]').prop('checked', true)
    //         $("#Allcat").prop("checked", true);
    //     }

    //     $.ajax({
    //         url: base_url + "search_features",
    //         method: 'GET',
    //         data: {
    //             search: search_value
    //         },
    //         success: function (response) {

    //             if (response.view) {
    //                 $('.search_category').html('');
    //                 $('.search_category').html(response.view);
    //                 $('#home_loader').css('display', 'none');
    //                 $('.total_design_count').text(response.count + ' Items')

    //             } else {
    //                 $('.search_category').html('No Design Found');
    //                 $('.total_design_count').text(response.count + ' Items')
    //                 $('#home_loader').css('display', 'none');
    //             }

    //         },
    //         error: function (error) {
    //             toastr.error('Some thing went wrong');
    //         }
    //     });
    // });
});
document.querySelectorAll('.collection-menu').forEach((button) => {
    button.addEventListener('click', (event) => {
        event.stopPropagation();
    });
});

const $cookiesBox = $('.cookies-track');

if (!localStorage.getItem('cookiesBoxDismissed')) {
    setTimeout(() => {
        // $cookiesBox.addClass('active');
        $('.cookies-track').css('display','block');

    }, 500);
}

$('.close-btn-privacy-cookie').on('click', function () {
    // $cookiesBox.removeClass('active');
    $('.cookies-track').css('display','none');

    localStorage.setItem('cookiesBoxDismissed', 'true');
});




$(document).on('change', 'input[name="design_subcategory"]:not(#Allcat)', function () {
    $(".image-item").hide(); // Hide default images
    $(".image-item-new").hide(); // Hide new items initially

    $('input[name="design_subcategory"]:checked').each(function () {
        const categoryId = $(this).data('category-id');
        const subcategoryId = $(this).data('subcategory-id');

        // Show filtered images
        $(`.image-item-new[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`).show();
    });

    var visibleItems = $('.image-item-new:visible').length;
    $('.total_design_count').text(visibleItems + ' Items');
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
                $('.total_design_count').text(response.total_textdatas + ' Items')

            } else {
                $('.list_all_design_catgeory').html('No Design Found');
                $('.total_design_count').text(response.total_textdatas + ' Items')
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
