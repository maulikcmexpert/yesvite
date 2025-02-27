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
    $(".image-item").show(); // Show all default images
    $(".image-item-new").hide(); // Hide new images initially
    $('input[name="design_subcategory"]').prop('checked', false);
    $('#Allcat').prop('checked', false);
    updateTotalCount();
});

// Handle "Allcat" checkbox change event
$('#Allcat').on('change', function () {
    if ($(this).is(':checked')) {
        // Show all default images and hide new images
        $('.image-item').show();
        $('.image-item-new').hide();

        // Hide category name and checkbox container
        $("#category_name").hide();
        $("#allchecked").hide();

        // Check all subcategory checkboxes
        $('input[name="design_subcategory"]').prop('checked', true);
    } else {
        // Uncheck all subcategories
        $('input[name="design_subcategory"]').prop('checked', false);

        // Hide all images
        $('.image-item').hide();
        $('.image-item-new').hide();
    }

    updateTotalCount();
});

// Handle individual subcategory checkbox change
$(document).on('change', 'input[name="design_subcategory"]:not(#Allcat)', function () {
    $(".image-item").hide(); // Hide all default images
    $(".image-item-new").hide(); // Hide all new images

    let anyChecked = false;

    $('input[name="design_subcategory"]:checked').each(function () {
        const categoryId = $(this).data('category-id');
        const subcategoryId = $(this).data('subcategory-id');

        // Show filtered images matching checked categories and subcategories
        $(`.image-item-new[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`).show();
        anyChecked = true;
    });

    if (!anyChecked) {
        // If no checkboxes are checked, show default images again
        $(".image-item").show();
    }

    updateTotalCount();
});

// Function to update total count of visible items
function updateTotalCount() {
    var visibleItems = $('.image-item:visible, .image-item-new:visible').length;
    $('.total_design_count').text(visibleItems + ' Items');
}


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
    let searchText = $(this).val().trim().toLowerCase();
    let resultsContainer = $('#filtered_results'); // New div for displaying filtered results
    resultsContainer.empty().show(); // Clear previous results and show the container

    $(".categoryNew").show();
    $(".subcategoryNew, .image-item-new").hide();
    $("#category_name, #allchecked").hide();

    let hasResults = false;

    // Loop through categories and subcategories
    $('.accordion-item').each(function () {
        let categoryName = $(this).find('.accordion-button').text().toLowerCase();
        let matchFound = categoryName.includes(searchText);

        $(this).find('li').each(function () {
            let subcategoryName = $(this).find('label').text().toLowerCase();
            if (subcategoryName.includes(searchText)) {
                hasResults = true;
                let subcategoryHtml = `<div class="filtered-item">${$(this).find('label').text()}</div>`;
                resultsContainer.append(subcategoryHtml);
            }
        });

        $(this).hide();
    });

    if (!hasResults) {
        resultsContainer.html('<p class="no-results">No results found</p>');
    }

    $('#home_loader').css('display', 'flex');

    if (searchText === '') {
        $('input[name="design_subcategory"]').prop('checked', true);
        $("#Allcat").prop("checked", true);
        $('.accordion-item').show();
        $('#home_loader').css('display', 'none');
        resultsContainer.hide();
        return;
    }

    $.ajax({
        url: base_url + "search_features",
        method: 'GET',
        data: { search: searchText },
        success: function (response) {
            $('#home_loader').css('display', 'none');

            if (response.view) {
                $('.list_all_design_catgeory').html(response.view);
                $('.total_design_count').text(response.count + ' Items');
            } else {
                $('.list_all_design_catgeory').html('<p>No Design Found</p>');
                $('.total_design_count').text('0 Items');
            }
        },
        error: function () {
            $('#home_loader').css('display', 'none');
            toastr.error('Something went wrong');
        }
    });
});

// Handle Click on Filtered Item
$(document).on('click', '.filtered-item', function () {
    let selectedText = $(this).text().trim();
    $('#search_design_category').val(selectedText);
    $('#filtered_results').hide(); // Hide results after selection
});

$(document).on('click', '.filtered-item', function () {
    let selectedText = $(this).text().trim();
    $('#search_design_category').val(selectedText);
    $('#filtered_results').hide(); // Hide the results after selection
});

// Show subcategories when clicking a category
$(document).on('click', '.image-item', function () {
    let categoryId = $(this).data('category-id');

    $(".subcategoryNew").hide();
    $(`.subcategoryNew[data-category-id="${categoryId}"]`).show(); // Show relevant subcategories
});

// Show designs when clicking a subcategory
$(document).on('click', '.subcategory-item', function () {
    let subcategoryId = $(this).data('subcategory-id');

    $(".image-item-new").hide();
    $(`.image-item-new[data-subcategory-id="${subcategoryId}"]`).show(); // Show relevant designs
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
