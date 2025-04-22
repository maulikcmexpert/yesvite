$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const designId = urlParams.get("design_id"); // Get 'design_id' from URL

    // if (designId) {
    //     // Find the element with class 'edit_design_tem' and matching data-id, then trigger click
    //     $('.edit_design_tem[data-id="' + designId + '"]').trigger('click');
    //     urlParams.delete('design_id');
    //     const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
    //     window.history.replaceState(null, '', newUrl);
    // }
    var base_url = $("#base_url").val();
    $(document).on("click", ".edit_design_tem", function () {
        var id = $(this).attr("data-id");
        var image = $(this).attr("data-image");
        var category_name = $(this).attr("data-subcategory_name");
        localStorage.setItem("image", image);
        localStorage.setItem("category_name", category_name);
        window.location.href = base_url + "events?design_id=" + id;
    });
    // const urlParams = new URLSearchParams(window.location.search);
    $(".default_show").show();

    $('input[name="design_subcategory"]').prop("checked", false);
    $("#Allcat").prop("checked", false);
    $(".side-bar-sub-list").click(function() {
        updateTotalCount();
    })

    updateTotalCount();

    // $('input[type="checkbox"]:not(#Allcat)').prop('checked', true);

    $("#Allcat").on("change", function () {
        $(".image-item").show(); // Show all default images

        if ($(this).is(":checked")) {
            // Show all default images and hide new images
            $(".default_show").show();
            $(".image-item-new").hide();

            // Hide category name and checkbox container
            $("#category_name").hide();
            $("#allchecked").hide();

            // Check all subcategory checkboxes
            $('input[name="design_subcategory"]').prop("checked", true);
        } else {
            // Uncheck all subcategories
            $('input[name="design_subcategory"]').prop("checked", false);

            $(".image-item").removeClass("d-none");
            // Hide all images
            $(".default_show").show();
            $(".image-item-new").hide();
        }

        updateTotalCount();
    });

    // Handle individual subcategory checkbox change
    $(document).on(
        "change",
        'input[name="design_subcategory"]:not(#Allcat)',
        function () {
            alert();
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

            // $('input[name="design_subcategory"]:checked').each(function () {
            //     default_s++;
            //     $(".image-item").removeClass("d-none");
            //     const categoryId = $(this).data("category-id");
            //     const subcategoryId = $(this).data("subcategory-id");

            //     // Show filtered images matching checked categories and subcategories
            //     $(
            //         `.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`
            //     ).show();
            // });

            // $('input[name="design_subcategory"]:checked').each(function () {
            //     default_s++;
            //     $(".image-item").removeClass("d-none");
            
            //     const categoryId = $(this).data("category-id");
            //     const subcategoryId = $(this).data("subcategory-id");
            
            //     $(".image-item").each(function () {
            //         const imgCategoryId = $(this).data("category-id");
            //         const imgSubcategoryIds = $(this).data("subcategory-id").toString().split(',');
            
            //         if (
            //             imgCategoryId == categoryId &&
            //             imgSubcategoryIds.includes(subcategoryId.toString())
            //         ) {
            //             // alert();
            //             // $(this).removeClass("d-none");      
            //             // $(this).show();
            //             $(this).show();
            //             $(this).removeClass("d-none");
            //             $(this).removeClass("fadeInDown");
            //             $(this).css("visibility", "visible");
            //             $(this).removeClass("wow");
            //             $(this).removeClass("d-none").fadeIn();
            //         }else{
            //             $(this).hide();
            //             $(this).fadeOut().addClass("d-none");
            //         }
            //     });
            // });
            
           // Step 1: Collect selected subcategory IDs
let selectedSubcategories = [];

$('input[name="design_subcategory"]:checked').each(function () {
    const subcategoryId = $(this).data("subcategory-id").toString();
    selectedSubcategories.push(subcategoryId);
});

// Step 2: Show only matching images
let visibleCount = 0;

$(".image-item").each(function () {
    const imgSubcategoryIds = $(this).data("subcategory-id").toString().split(',');

    // Check if any selected subcategory matches
    const isMatch = selectedSubcategories.some(id => imgSubcategoryIds.includes(id));

    if (isMatch) {
        $(this)
            .show()
            .removeClass("d-none fadeInDown wow")
            .css("visibility", "visible")
            .fadeIn();
        visibleCount++;
    } else {
        $(this).fadeOut().addClass("d-none");
    }
});

// Step 3: Update visible count
// $(".total_design_count").text(`${visibleCount} Items`);

            
            if (default_s == 0) {
                $(".image-item").removeClass("d-none");
                $(".default_show").show();
            }
            updateTotalCount();
        }
    );
    $(document).on("click", ".selected-items .close-btn", function () {
        var parent = $(this).parent();
        var value = parent.attr("data-value");

        // Uncheck the corresponding checkbox
        $('input[name="design_subcategory"][value="' + value + '"]')
            .prop("checked", false)
            .trigger("change");

        // Remove the selected item from the list
        parent.remove();
    });
    // Function to update total count of visible items
    function updateTotalCount() {
        var visibleItems = $(
            ".image-item:visible, .image-item-new:visible"
        ).length;
        $(".total_design_count").text(visibleItems + " Items");
    }

    $("#resetCategories").on("click", function (e) {
        e.preventDefault();
        $(".categoryNew").show();
        $(".subcategoryNew").hide();
        $(".image-item-new").hide(); // Hide filtered items
        $(".image-item").show(); // Show default images
        $("#category_name").hide();
        $("#allchecked").hide();
        $("#Allcat").prop("checked", false);
        $('input[name="design_subcategory"]:not(#Allcat)').prop(
            "checked",
            false
        );
        $(".selected-items").empty();
        var visibleItems = $(".image-item:visible").length;
        $(".total_design_count").text(
            $(".default_show:visible").length + " Items"
        );
    });
    $("#filtered_results").hide();
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

    // $("#search_design_category").on("keyup", function () {
    //     let query = $(this).val().toLowerCase().trim();
    //     let results = "";
    //     if (query.length > 0) {
    //         let visibleCount = 0;

    //         $(".image-item").each(function () {
    //             // let tags = $(this).data("tags")
    //             //     ? $(this).data("tags").toLowerCase().split(",")
    //             //     : [];

    //             let tags = $(this).data("tags")
    //             ? $(this).data("tags").toLowerCase().split(",")
    //             : [];
    //             let subcategory = $(this).data("subcategory_name")
    //                 ? $(this).data("subcategory_name").toLowerCase()
    //                 : "";
    //             let category = $(this).data("category_name")
    //                 ? $(this).data("category_name").toLowerCase()
    //                 : "";


    //                 let matches = tags.some((tag) => tag.includes(query)) ||
    //                 subcategory.includes(query) ||
    //                 category.includes(query);

    //                 if (matches) {
    //                     $(this).show();
    //                         $(this).removeClass("d-none");
    //                         $(this).removeClass("fadeInDown");
    //                         $(this).css("visibility", "visible");
    //                         $(this).removeClass("wow");
    //                         $(this).removeClass("d-none").fadeIn();
    //                         visibleCount++;
    //                 } else {
    //                     $(this).hide();
    //                     $(this).fadeOut().addClass("d-none");
    //                 }
    //             // if (tags.some((tag) => tag.includes(query))) {
    //             //     $(this).show();
    //             //     $(this).removeClass("d-none");
    //             //     $(this).removeClass("fadeInDown");
    //             //     $(this).css("visibility", "visible");
    //             //     $(this).removeClass("wow");
    //             //     $(this).removeClass("d-none").fadeIn();
    //             //     visibleCount++;
    //             // } else {
    //             //     $(this).hide();
    //             //     $(this).fadeOut().addClass("d-none");
    //             // }
    //         });

    //         console.log("Total Visible Items:", visibleCount);
    //         $(".total_design_count").text(visibleCount + " Items");

    //         if (visibleCount > 0) {
    //             $("#filtered_results").hide();
    //         } else {
    //             $("#filtered_results").show();
    //         }

    //         if ($(".image-item:visible").length === 0) {
    //             $(".total_design_count").text(
    //                 $(".image-item:visible").length + " Items"
    //             );

    //             results += `<div class="search-item no-data">No Data Found</div>`;
    //             $("#filtered_results").show();
    //             $("#filtered_results").html(results);
    //         }
    //     } else {
    //         $(".image-item").removeClass("d-none fadeInDown wow").show();
    //         let allItems = $(".image-item");
    //         if (allItems.length > 30) {
    //             allItems.slice(30).addClass("d-none").hide();
    //         }
    //         $(".total_design_count").text(
    //             $(".image-item:visible").length + " Items"
    //         );
    //         $("#filtered_results").hide();
    //     }

    //     // $('#filtered_results').html(results);
    // });


    //today old code...........
        $("#search_design_category").on("keyup", function () {
            let query = $(this).val().toLowerCase().trim();
            $('.selected-items').html('');

            let results = "";
            if (query.length > 0) {
                $('.selected-items').html('');

                designData.forEach(category => {
                    if (category.name.toLowerCase().includes(query)) {
                        results +=
                            `<div class="search-item category"  data-category-id="${category.id}"  data-name="${category.name}">${category.name}</div>`;
                    }
                    // Check if no subcategory matched and add "No Data Found"
    
                    category.subcategories.forEach(subcategory => {
                        if (subcategory.name.toLowerCase().includes(query)) {
                            results +=
                                `<div class="search-item subcategory" data-id="${subcategory.id}" data-category-id="${category.id}" data-name="${subcategory.name}">${subcategory.name}</div>`;
                        }
                        // Check if no subcategory matched and add "No Data Found"
                    });
                });
                if (results == '') {
                    $('#filtered_results').hide();
                } else {
                    $('#filtered_results').html(results).show();
                }

                let visibleCount = 0;
        
                $(".image-item").each(function () {
                    let tags = $(this).data("tags")
                        ? $(this).data("tags").toLowerCase().split(",")
                        : [];
                    let subcategories = $(this).data("subcategory_name")
                        ? $(this).data("subcategory_name").toLowerCase().split(",") // Split subcategories by comma
                        : [];
                    let category = $(this).data("category_name")
                        ? $(this).data("category_name").toLowerCase()
                        : "";
        
                    // Check if any of the search criteria match
                    let matches = tags.some((tag) => tag.includes(query)) ||
                        subcategories.some((subcategory) => subcategory.includes(query)) || // Check each subcategory
                        category.includes(query);
        
                    if (matches) {
                        $(this).show();
                        $(this).removeClass("d-none");
                        $(this).removeClass("fadeInDown");
                        $(this).css("visibility", "visible");
                        $(this).removeClass("wow");
                        $(this).removeClass("d-none").fadeIn();
                        visibleCount++;
                    } else {
                        $(this).hide();
                        $(this).fadeOut().addClass("d-none");
                    }
                });
        
                console.log("Total Visible Items:", visibleCount);
                $(".total_design_count").text(visibleCount + " Items");
        
                if (visibleCount > 0) {
                    // $("#filtered_results").hide();
                } else {
                    $("#filtered_results").show();
                }
        
                if ($(".image-item:visible").length === 0) {
                    $(".total_design_count").text(
                        $(".image-item:visible").length + " Items"
                    );
        
                    results += `<div class="search-item no-data">No Data Found</div>`;
                    $("#filtered_results").show();
                    $("#filtered_results").html(results);
                }
            } else {
                
                $(".image-item").removeClass("d-none fadeInDown wow").show();
                let allItems = $(".image-item");
                if (allItems.length > 30) {
                    allItems.slice(30).addClass("d-none").hide();
                }
                $(".total_design_count").text(
                    $(".image-item:visible").length + " Items"
                );

                $("#filtered_results").hide();
            }
        });
 
        // $(document).on("click", ".search-item", function () {
        //     let selectedText = $(this).data("name");
        //     let categoryId = $(this).data("category-id");
        //     let subcategoryId = $(this).data("id");

        //     $("#search_design_category").val(selectedText);
        //     $("#filtered_results").html(""); // Clear search results
        //     $("#filtered_results").hide();
        //     $(".image-item").hide();

        //     if (categoryId && subcategoryId) {
        //         // Show only images that match category and subcategory
        //         $(
        //             `.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`
        //         ).show();
        //     } else if (categoryId) {
        //         $(`.image-item[data-category-id="${categoryId}"]`).show();
        //     }

        //     $(
        //         `input[name="design_subcategory"][data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`
        //     ).prop("checked", true);
        //     // if ($(this).hasClass('subcategory')) {

        //     //     let images = designData.find(c => c.id == categoryId)
        //     //         .subcategories.find(s => s.id == subcategoryId).images;

        //     //     // Auto-check the corresponding subcategory checkbox
        //     //
        //     // }

        //     $(".total_design_count").text(
        //         $(".image-item:visible").length + " Items"
        //     );
        // });
    //tody old code...........



    // $("#search_design_category").on("keyup", function () {
    //     let query = $(this).val().toLowerCase().trim();
    //     let results = "";
    
    //     if (query.length > 0) {
    //         let suggestionSet = new Set();
    //         let addedSubcategories = new Set();
    //         let addedTags = new Set();
    //         let addedCategories = new Set();
    
    //         $(".image-item").each(function () {
    //             let $item = $(this);
    //             let category = $item.data("category_name") ? $item.data("category_name").toLowerCase().trim() : "";
    //             let subcategories = $item.data("subcategory_name") ? $item.data("subcategory_name").toLowerCase().split(",") : [];
    //             let tags = $item.data("tags") ? $item.data("tags").toLowerCase().split(",") : [];
    
    //             let categoryId = $item.data("category-id");
    //             let subcategoryId = $item.data("subcategory-id");
    
    //             if (category.includes(query) && !addedCategories.has(category)) {
    //                 suggestionSet.add(`<div class="search-item category" data-name="${category}" data-category-id="${categoryId}">Cat :${category}</div>`);
    //                 addedCategories.add(category);
    //             }
    
    //             subcategories.forEach(subcat => {
    //                 let subcatTrimmed = subcat.trim();
    //                 if (subcatTrimmed.includes(query) && !addedSubcategories.has(subcatTrimmed)) {
    //                     suggestionSet.add(`<div class="search-item subcategory" data-name="${subcatTrimmed}" data-category-id="${categoryId}" data-id="${subcategoryId}">SubCat :${subcatTrimmed}</div>`);
    //                     addedSubcategories.add(subcatTrimmed);
    //                 }
    //             });
    
    //             tags.forEach(tag => {
    //                 let tagTrimmed = tag.trim();
    //                 if (tagTrimmed.includes(query) && !addedTags.has(tagTrimmed)) {
    //                     suggestionSet.add(`<div class="search-item tag" data-name="${tagTrimmed}">Tags :${tagTrimmed}</div>`);
    //                     addedTags.add(tagTrimmed);
    //                 }
    //             });
    //         });
    
    //         results = Array.from(suggestionSet).join("");
    
    //         if (results.length > 0) {
    //             $("#filtered_results").show().html(results);
    //         } else {
    //             $("#filtered_results").show().html(`<div class="search-item no-data">No Data Found</div>`);
    //         }
    
    //     } else {
    //         $("#filtered_results").hide();
    //         $(".image-item").removeClass("d-none fadeInDown wow").show();
    
    //         let allItems = $(".image-item");
    //         if (allItems.length > 30) {
    //             allItems.slice(30).addClass("d-none").hide();
    //         }
    
    //         $(".total_design_count").text($(".image-item:visible").length + " Items");
    //     }
    // });
    
    // $(document).on("click", ".search-item", function () {
    //     let selectedText = $(this).data("name")?.toLowerCase();
    //     let categoryId = $(this).data("category-id");
    //     let subcategoryId = $(this).data("id");
    
    //     $("#search_design_category").val(selectedText);
    //     $("#filtered_results").html("").hide();
    
    //     $(".image-item").hide();
    //     let matchCount = 0;
    
    //     if ($(this).hasClass("category")) {
    //         $(`.image-item[data-category-name*="${selectedText}"]`).each(function () {
    //                             $(this).show();
    //                             $(this).removeClass("d-none");
    //                             $(this).removeClass("fadeInDown");
    //                             $(this).css("visibility", "visible");
    //                             $(this).removeClass("wow");
    //                             $(this).removeClass("d-none").fadeIn();
                
    //             matchCount++;
    //         });
    //     }else if ($(this).hasClass("subcategory")) {
    //         $(".image-item").each(function () {
    //             let subcategories = $(this).data("subcategory_name") ? $(this).data("subcategory_name").toLowerCase().split(",") : [];
    //             if (subcategories.some(subcat => subcat.trim() === selectedText)) {
    //                 $(this).show();
    //                 $(this).removeClass("d-none");
    //                 $(this).removeClass("fadeInDown");
    //                 $(this).css("visibility", "visible");
    //                 $(this).removeClass("wow");
    //                 $(this).removeClass("d-none").fadeIn();
    //                                     matchCount++;
    //             }
    //         });
    
    //         // Also check the corresponding input
    //         $(`input[name="design_subcategory"][data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`).prop("checked", true);
    //     } else if ($(this).hasClass("tag")) {
    //         $(".image-item").each(function () {
    //             let tags = $(this).data("tags") ? $(this).data("tags").toLowerCase().split(",") : [];
    //             if (tags.includes(selectedText)) {
    //                 $(this).show();
    //                 $(this).removeClass("d-none");
    //                 $(this).removeClass("fadeInDown");
    //                 $(this).css("visibility", "visible");
    //                 $(this).removeClass("wow");
    //                 $(this).removeClass("d-none").fadeIn();
    //                 matchCount++;
    //             }
    //         });
    //     }
    
    //     $(".total_design_count").text(matchCount + " Items");
    // });
        


    $(document).on("click", ".search-item", function () {
        $('input[name="design_subcategory"]').prop("checked", false);

        let selectedText = $(this).data("name");
        let categoryId = $(this).data("category-id");
        let subcategoryId = $(this).data("id");
    
        $("#search_design_category").val(selectedText);
        $("#filtered_results").html("").hide();
        $(".image-item").hide();
    
     
        if (categoryId && subcategoryId) {
            $(`.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`).show();
        } else if (categoryId) {
            $(`.image-item[data-category-id="${categoryId}"]`).show();
        }

        $(".image-item").each(function () {
            let tags = $(this).data("tags")
                ? $(this).data("tags").toLowerCase().split(",")
                : [];
            let subcategories = $(this).data("subcategory_name")
                ? $(this).data("subcategory_name").toLowerCase().split(",")
                : [];
            let category = $(this).data("category_name")
                ? $(this).data("category_name").toLowerCase()
                : "";
    
            let matches = tags.some(tag => tag.includes(selectedText.toLowerCase())) ||
                subcategories.some(sub => sub.includes(selectedText.toLowerCase())) ||
                category.includes(selectedText.toLowerCase());
    
            if (matches) {
                $(this).show().removeClass("d-none").css("visibility", "visible").fadeIn();
            } else {
                $(this).fadeOut().addClass("d-none");
            }
        });
    
        $(".total_design_count").text(
            $(".image-item:visible").length + " Items"
        );
    });
    
    let previousSearch = "";

    $("#search_design_category").on("input", function () {
        let query = $(this).val().trim();

        if (query === "") {
            $("#filtered_results").html("").addClass("d-none");

            // Show only default images
            $(".default_show").show();

            // Ensure checked subcategories are unchecked
            $('input[name="design_subcategory"]').prop("checked", false);

            // Update the total count of visible images
            updateTotalCount();
        } else {
            $("#filtered_results").removeClass("d-none");

            // Check if the first character of the previous search is different from the current one
            if (
                previousSearch.length > 0 &&
                previousSearch.charAt(0).toLowerCase() !==
                    query.charAt(0).toLowerCase()
            ) {
                $('input[name="design_subcategory"]').prop("checked", false);
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

$(document).on(
    "change",
    'input[name="design_subcategory"]:not(#Allcat)',
    function () {
        $(".image-item").hide(); // Hide default images
        $(".image-item-new").hide(); // Hide new items initially

        $('input[name="design_subcategory"]:checked').each(function () {
            const categoryId = $(this).data("category-id");
            const subcategoryId = $(this).data("subcategory-id");

            // Show filtered images
            $(
                `.image-item-new[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`
            ).show();
        });

        var visibleItems = $(".image-item-new:visible").length;
        $(".total_design_count").text(visibleItems + " Items");
    }
);

$(document).on("click", "#allchecked", function () {
    const categoryId = $(this).attr("data-categoryid");
    const subcategoryId = $(this).attr("data-subcategoryid");
    allCheckFun(categoryId, subcategoryId);
});

function allCheckFun(categoryIds, subcategoryIds) {
    $('input[name="design_subcategory_new"]').prop("checked", false);
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

    $('input[name="design_category"]:not(#Allcat):checked').each(function () {
        const categoryId = $(this).data("category-id");

        const subcategoryId = $(this).data("subcategory-id");

        // // Show images matching the selected categories and subcategories
        $(
            `.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`
        ).show();
        var visibleItems = $(".all_designs:visible").length;
        $(".total_design_count").text(visibleItems + " Items");
    });

    // let totalCheckboxes = $('input[name="design_subcategory_new"]:not(#Allcat)').length;

    // let checkedCheckboxes = $(`.subcategory_${categoryIds}:not(#Allcat):checked`).length;

    // if(checkedCheckboxes == 0){
    //     $('.categoryChecked_'+categoryIds).prop('checked',false);
    //     $(`.image-item[data-category-id="${categoryIds}"]`).hide();
    // }

    $(`.subcategoryChecked_${subcategoryIds}:checked`).each(function () {
        $(
            `.image-item-new[data-category-id="${categoryIds}"][data-subcategory-id="${subcategoryIds}"]`
        ).show();
        $(".subcategoryChecked_" + subcategoryIds).prop("checked", false);
    });

    if ($("#search_design_category").val() == "") {
        return;
    }
    $("#search_design_category").val("");
    let search_value = "";
    $.ajax({
        url: base_url + "search_design",
        method: "GET",
        data: {
            search: search_value,
        },
        success: function (response) {
            if (response.view) {
                $(".list_all_design_catgeory").html("");
                $(".list_all_design_catgeory").html(response.view);
                $("#home_loader").css("display", "none");
                $(".total_design_count").text(
                    response.total_textdatas + " Items"
                );
            } else {
                $(".list_all_design_catgeory").html("No Design Found");
                $(".total_design_count").text(
                    response.total_textdatas + " Items"
                );
                $("#home_loader").css("display", "none");
            }
        },
        error: function (error) {
            toastr.error("Some thing went wrong");
        },
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
