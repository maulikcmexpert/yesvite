$("#Allcat").prop("checked", true);

$(document).ready(function () {
    $('input[name="design_subcategory"]').prop("checked", false);
    $("#Allcat").prop("checked", false);
    updateItemCount();

    $("#Allcat").on("change", function () {
        $(".categoryNew").show();
        $(".subcategoryNew").hide();
        $(".filter_category_new").show();
        $(".filter_category").hide();
        $("#category_name, #allchecked").hide();

        if ($(this).is(":checked")) {
            $('input[name="design_subcategory"]:not(#Allcat)').prop("checked", true);
            $(".image-item").show();
        } else {
            $('input[name="design_subcategory"]:not(#Allcat)').prop("checked", false);
            $(".image-item").hide();
        }
        updateItemCount();
    });

    $(document).on("change", 'input[name="design_subcategory"]:not(#Allcat)', function () {
        $(".filter_category_new").show();
        $(".filter_category").hide();
        $(".categoryNew").show();
        $(".subcategoryNew, #category_name, #allchecked").hide();

        const totalCheckboxes = $('input[name="design_subcategory"]:not(#Allcat)').length;
        const checkedCheckboxes = $('input[name="design_subcategory"]:not(#Allcat):checked').length;

        $("#Allcat").prop("checked", checkedCheckboxes === totalCheckboxes);

        if (checkedCheckboxes > 0) {
            $(".image-item").hide();
            $('input[name="design_subcategory"]:checked').each(function () {
                const categoryId = $(this).data("category-id");
                const subcategoryId = $(this).data("subcategory-id");
                $(`.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`).show();
            });
        } else {
            $(".image-item").show();
        }
        updateItemCount();
    });

    $("#resetCategories").on("click", function (e) {
        e.preventDefault();
        resetFilters();
    });

    $("#resetCategoriesNew").on("click", function (e) {
        e.preventDefault();
        resetFilters();
    });

    $(".design_category").on("click", function () {
        var visibleItems = $(".all_designs:visible").length;
        alert(visibleItems);
        $(".total_design_count").text(visibleItems + " Items");
    });

    function updateItemCount() {
        let visibleItems = $(".all_designs:visible").length;
        $(".total_design_count").text(visibleItems + " Items");
    }

    function resetFilters() {
        $(".categoryNew").show();
        $(".subcategoryNew").hide();
        $(".image-item-new, #category_name, #allchecked").hide();
        $("#Allcat").prop("checked", false);
        $('input[name="design_subcategory"]:not(#Allcat)').prop("checked", false);
        $(".image-item").hide();
        updateItemCount();
    }
});
$('input[name="design_subcategory_new"]').on('change', function() {
    let subcategoryId = $(this).data('subcategory-id');

    // If checked, check the corresponding checkbox in design_subcategory
    if ($(this).is(':checked')) {
        $('input[name="design_subcategory"][data-subcategory-id="' + subcategoryId + '"]').prop(
            'checked', true);
        $('#Allcat').prop('checked', true);
    } else {
        // If unchecked, uncheck the corresponding checkbox in design_subcategory
        $('input[name="design_subcategory"][data-subcategory-id="' + subcategoryId + '"]').prop(
            'checked', false);
        $('#Allcat').prop('checked', false);
    }
});
$(document).on("click", "#allchecked", function () {
    const categoryId =  $(this).attr('data-categoryid');
    allCheckFun(categoryId)
});

function allCheckFun(categoryIds) {
    $('input[name="design_subcategory_new"]').prop("checked", false);
    // $('input[name="design_subcategory"]').prop('checked', true)
    $(".categoryNew").show();
    $(".subcategoryNew").hide();
    $(".image-item-new").hide();
    $("#category_name").hide();
    $("#allchecked").hide();
    // $('input[name="design_subcategory"]:not(#Allcat)').prop("checked", true);
    // $("#Allcat").prop("checked", true);
    // $(".image-item").show();
    var visibleItems = $(".all_designs:visible").length;
    $(".total_design_count").text(visibleItems + " Items");

    $('input[name="design_subcategory"]:not(#Allcat):checked').each(
        function () {

            const categoryId = $(this).data("category-id");

            // const subcategoryId = $(this).data("subcategory-id");

            // // Show images matching the selected categories and subcategories
            $(`.image-item[data-category-id="${categoryId}"]`).show();
            var visibleItems = $(".all_designs:visible").length;
            $(".total_design_count").text(visibleItems + " Items");
        }
    );
    // let totalCheckboxes = $('input[name="design_subcategory_new"]:not(#Allcat)').length;
    //         let checkedCheckboxes = $('input[name="design_subcategory_new"]:not(#Allcat):checked').length;
    //         if(checkedCheckboxes == 0){
    //             $('.categoryChecked_'+categoryIds).prop('checked',false);
    //             $(`.image-item[data-category-id="${categoryIds}"]`).hide();
    //         }
    let search_value = "";
    if ($("#search_design_category").val() == "") {
        return;
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

$(document).on(
    "change",
    'input[name="design_subcategory_new"]:not(#Allcat)',
    function () {
        $(".image-item-new").hide();
        $("#category_name").show();
        $("#allchecked").show();
        // If all individual checkboxes are checked, check "All Categories"
        const totalCheckboxes = $(
            'input[name="design_subcategory_new"]:not(#Allcat)'
        ).length;
        const checkedCheckboxes = $(
            'input[name="design_subcategory_new"]:not(#Allcat):checked'
        ).length;

        // Filter images based on checked categories
        if (checkedCheckboxes > 0) {
            $(".image-item").hide(); // Hide all images first
            $('input[name="design_subcategory_new"]:not(#Allcat):checked').each(
                function () {
                    const categoryId = $(this).data("category-id");
                    const subcategoryId = $(this).data("subcategory-id");

                    $(
                        `.image-item-new[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`
                    ).show();

                    var visibleItems = $(".all_designs:visible").length;
                    $(".total_design_count").text(visibleItems + " Items");
                }
            );
        } else {
            $(".image-item-new").hide(); // Hide all images if no checkboxes are checked
            var visibleItems = $(".all_designs:visible").length;
            $(".total_design_count").text(visibleItems + " Items");
        }
    }
);
