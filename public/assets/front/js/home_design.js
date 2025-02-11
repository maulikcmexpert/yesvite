// $(document).ready(function() {
//     // $('input[type="checkbox"]:not(#Allcat)').prop('checked', true);
//     $('input[name="design_subcategory"]').prop('checked', true);
//     $('#Allcat').prop('checked', true);

//     $('#Allcat').on('change', function() {
//         if ($(this).is(':checked')) {
//             $('input[name="design_subcategory"]:not(#Allcat)').prop('checked', true);
//             $('.image-item').show();
//             var visibleItems = $('.all_designs:visible').length;
//             $('.total_design_count').text(visibleItems + ' Items');
//         } else {
//             $('input[name="design_subcategory"]:not(#Allcat)').prop('checked', false);
//             $('.image-item').hide();
//             var visibleItems = $('.all_designs:visible').length;
//             $('.total_design_count').text(visibleItems + ' Items');
//         }
//     });

//     $(document).on('change', 'input[name="design_subcategory"]:not(#Allcat)', function() {
//         // If all individual design_subcategoryes are checked, check "All Categories"
//         const totalCheckboxes = $('input[name="design_subcategory"]:not(#Allcat)').length;
//         const checkedCheckboxes = $('input[name="design_subcategory"]:not(#Allcat):checked').length;

//         if (checkedCheckboxes === totalCheckboxes) {
//             $('#Allcat').prop('checked', true);
//         } else {
//             $('#Allcat').prop('checked', false);
//         }

//         // Filter images based on checked categories
//         if (checkedCheckboxes > 0) {
//             $('.image-item').hide(); // Hide all images first
//             $('input[name="design_subcategory"]:not(#Allcat):checked').each(function() {
//                 const categoryId = $(this).data('category-id');
//                 const subcategoryId = $(this).data('subcategory-id');

//                 // Show images matching the selected categories and subcategories
//                 $(`.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`)
//                     .show();
//                     var visibleItems = $('.all_designs:visible').length;
//                     $('.total_design_count').text(visibleItems + ' Items');
//             });
//         } else {
//             $('.image-item').hide(); // Hide all images if no checkboxes are checked
//             var visibleItems = $('.all_designs:visible').length;
//             $('.total_design_count').text(visibleItems + ' Items');
//         }
//     });
//     $('#resetCategories').on('click', function(e) {
//          e.preventDefault();
//         $('#Allcat').prop('checked', false);
//         $('input[name="design_subcategory"]:not(#Allcat)').prop('checked', false);
//         $('.image-item').hide();
//         var visibleItems = $('.all_designs:visible').length;
//         $('.total_design_count').text(visibleItems + ' Items');
//     });

//     document.querySelectorAll('.collection-menu').forEach((button) => {
//         button.addEventListener('click', (event) => {
//             event.stopPropagation();
//         });
//     });

//     const $cookiesBox = $('.cookies-track');

//     if (!localStorage.getItem('cookiesBoxDismissed')) {
//         setTimeout(() => {
//             $cookiesBox.addClass('active');
//         }, 500);
//     }

//     $('.close-btn').on('click', function () {
//         $cookiesBox.removeClass('active');
//         localStorage.setItem('cookiesBoxDismissed', 'true');
//     });

//     $(document).on('input','#search_design_category',function(){
//         var search_value=$(this).val();
//         $('#home_loader').css('display','flex');
//         $.ajax({
//             url: base_url + "search_features",
//             method: 'GET',
//             data: { search: search_value},
//             success: function (response) {
//                 console.log("Remove successful: ", response);

//                 if (response.view) {
//                  $('.list_all_design_catgeory').html('');
//                  $('.list_all_design_catgeory').html(response.view);
//                  $('#home_loader').css('display','none');
//                  $('.total_design_count').text(response.count +' Items')

//                 } else {
//                     $('.list_all_design_catgeory').html('No Design Found');
//                     $('.total_design_count').text(response.count +' Items')
//                     $('#home_loader').css('display','none');
//                 }
//             },
//             error: function (error) {
//                toastr.error('Some thing went wrong');
//             }
//         });
//     });
// });

$("#Allcat").prop("checked", true);

$(document).ready(function () {
    // $('input[type="checkbox"]:not(#Allcat)').prop('checked', true);
    $('input[name="design_subcategory"]').prop("checked", true);
    $("#Allcat").prop("checked", true);

    $("#Allcat").on("change", function () {
        $(".image-item-new").hide();
        $("#category_name").hide();
        $("#allchecked").hide();
        if ($(this).is(":checked")) {
            $('input[name="design_subcategory"]:not(#Allcat)').prop(
                "checked",
                true
            );
            $(".image-item").show();
            var visibleItems = $(".all_designs:visible").length;
            $(".total_design_count").text(visibleItems + " Items");
        } else {
            $('input[name="design_subcategory"]:not(#Allcat)').prop(
                "checked",
                false
            );
            $(".image-item").hide();
            var visibleItems = $(".all_designs:visible").length;
            $(".total_design_count").text(visibleItems + " Items");
        }
    });

    $(document).on(
        "change",
        'input[name="design_subcategory"]:not(#Allcat)',
        function () {
            $(".image-item-new").hide();
            $("#category_name").hide();
            $("#allchecked").hide();
            // If all individual checkboxes are checked, check "All Categories"
            const totalCheckboxes = $(
                'input[name="design_subcategory"]:not(#Allcat)'
            ).length;
            const checkedCheckboxes = $(
                'input[name="design_subcategory"]:not(#Allcat):checked'
            ).length;

            if (checkedCheckboxes === totalCheckboxes) {
                $("#Allcat").prop("checked", true);
            } else {
                $("#Allcat").prop("checked", false);
            }

            // Filter images based on checked categories
            if (checkedCheckboxes > 0) {
                $(".image-item").hide(); // Hide all images first
                $('input[name="design_subcategory"]:not(#Allcat):checked').each(
                    function () {
                        const categoryId = $(this).data("category-id");
                        const subcategoryId = $(this).data("subcategory-id");

                        // Show images matching the selected categories and subcategories
                        $(
                            `.image-item[data-category-id="${categoryId}"]`
                        ).show();
                        var visibleItems = $(".all_designs:visible").length;
                        $(".total_design_count").text(visibleItems + " Items");
                    }
                );
            } else {
                $(".image-item").hide(); // Hide all images if no checkboxes are checked
                var visibleItems = $(".all_designs:visible").length;
                $(".total_design_count").text(visibleItems + " Items");
            }
        }
    );
    $("#resetCategories").on("click", function (e) {
        $(".image-item-new").hide();
        $("#category_name").hide();
        $("#allchecked").hide();
        e.preventDefault();
        $("#Allcat").prop("checked", false);
        $('input[name="design_subcategory"]:not(#Allcat)').prop(
            "checked",
            false
        );
        $(".image-item").hide();
        var visibleItems = $(".all_designs:visible").length;
        $(".total_design_count").text(visibleItems + " Items");
    });

    document.querySelectorAll(".collection-menu").forEach((button) => {
        button.addEventListener("click", (event) => {
            event.stopPropagation();
        });
    });

    const $cookiesBox = $(".cookies-track");

    if (!localStorage.getItem("cookiesBoxDismissed")) {
        setTimeout(() => {
            $cookiesBox.addClass("active");
        }, 500);
    }

    $(".close-btn").on("click", function () {
        $cookiesBox.removeClass("active");
        localStorage.setItem("cookiesBoxDismissed", "true");
    });

    $(document).on("input", "#search_design_category", function () {
        $(".image-item-new").hide();
        $("#category_name").hide();
        $("#allchecked").hide();
        var search_value = $(this).val();
        $("#home_loader").css("display", "flex");
        $.ajax({
            url: base_url + "search_features",
            method: "GET",
            data: {
                search: search_value,
            },
            success: function (response) {
                if (response.view) {
                    $(".list_all_design_catgeory").html("");
                    $(".list_all_design_catgeory").html(response.view);
                    $("#home_loader").css("display", "none");
                    $(".total_design_count").text(response.count + " Items");
                } else {
                    $(".list_all_design_catgeory").html("No Design Found");
                    $(".total_design_count").text(response.count + " Items");
                    $("#home_loader").css("display", "none");
                }
            },
            error: function (error) {
                toastr.error("Some thing went wrong");
            },
        });
    });

    $(document).on("click", "#design_category", function () {
        $(".category").hide();
        $(".image-item-new").hide();
        $(".image-item").hide();
        const categoryId = $(this).data("category-id");
        $(".category_"+categoryId).show()
        const subcategoryId = $(this).data("subcategory-id");
        const category_name = $(this).data("category_name");
        $("#category_name").show();
        $("#allchecked").show();
        $("#category_name").text(category_name);

        $(`.image-item-new[data-category-id="${categoryId}"]`).show();
        var visibleItems = $(".all_designs:visible").length;
        $(".total_design_count").text(visibleItems + " Items");
    });
});

$(document).on("click", "#allchecked", function () {
    allCheckFun();
});

function allCheckFun() {
    $(".image-item-new").hide();
    $("#category_name").hide();
    $("#allchecked").hide();
    // $('input[name="design_subcategory"]:not(#Allcat)').prop("checked", true);
    $("#Allcat").prop('checked',true)
    $('.image-item').show();
    var visibleItems = $(".all_designs:visible").length;
    $(".total_design_count").text(visibleItems + " Items");
    let search_value = "";
    if($("#search_design_category").val() ==""){
        return
    }
    $("#search_design_category").val("");
    $.ajax({
        url: base_url + "search_features",
        method: "GET",
        data: {
            search: search_value,
        },
        success: function (response) {
            if (response.view) {
                $(".list_all_design_catgeory").html("");
                $(".list_all_design_catgeory").html(response.view);
                $("#home_loader").css("display", "none");
                $(".total_design_count").text(response.count + " Items");
            } else {
                $(".list_all_design_catgeory").html("No Design Found");
                $(".total_design_count").text(response.count + " Items");
                $("#home_loader").css("display", "none");
            }
        },
        error: function (error) {
            toastr.error("Some thing went wrong");
        },
    });
}
