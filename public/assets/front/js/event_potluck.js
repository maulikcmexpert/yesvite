$(document).ready(function () {
    // $('#categoryForm').on('submit', function (event) {
    //     event.preventDefault(); // Prevent default form submission

    //     // Clear previous error messages
    //     $('.error_message_category').text('');
    //     $('.error_message_quantity').text('');

    //     // Get input values
    //     var category = $('#category').val().trim();
    //     var quantity = parseInt($('#quantity').val());

    //     // Validation flags
    //     var valid = true;

    //     // Validate category
    //     if (category === "") {
    //         $('.error_message_category').text("Please enter a category.");
    //         valid = false;
    //     }

    //     // Validate quantity
    //     if (isNaN(quantity) || quantity <= 0) {
    //         $('.error_message_quantity').text("Please enter a valid quantity.");
    //         valid = false;
    //     }

    //     // Stop form submission if validation fails
    //     if (!valid) {
    //         return;
    //     }

    // });
    $("#category").on("input", function () {
        var maxLength = 30;
        var currentLength = $(this).val().length;

        // If current length exceeds max length, truncate the value
        if (currentLength > maxLength) {
            $(this).val($(this).val().substring(0, maxLength));
            currentLength = maxLength; // Ensure count doesn't go over
        }

        // Update the character count
        $("#charCount").text(currentLength);
    });
    $("#addCategoryModal").on("click", function () {
        $("#categoryForm")[0].reset(); // Reset the form
        $("#charCount").text("0"); // Reset the character count display
    });
    $("#deletemodal").on("show.bs.modal", function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var categoryId = button.data("category-id");
        var eventId = button.data("event-id");

        $("#confirmDeleteCategory")
            .data("category-id", categoryId)
            .data("event-id", eventId);
    });
    $('[data-bs-toggle="modal"][data-bs-target="#editcategorymodal"]').on(
        "click",
        function () {
            // Get data attributes from the clicked link
            const categoryId = $(this).data("category-id");
            const categoryName = $(this).data("category-name");
            const categoryQuantity = $(this).data("category-quantity");
            console.log(categoryId);
            console.log("Category Name:", categoryName);
            console.log("Category Quantity:", categoryQuantity);
            // Set the values in the modal form
            $("#categorys").val(categoryName); // Set category name in the input field
            $("#quantitys").val(categoryQuantity); // Set category quantity in the input field

            const formAction = `/event_potluck/updateCategory/${categoryId}) }}`;
            $("#categoryForms").attr("action", formAction);
        }
    );
    // Debugging to check if the modal is shown and values are set
    $("#confirmDeleteCategory").on("click", function () {
        var categoryId = $(this).data("category-id");
        var eventId = $(this).data("event-id");
        console.log(categoryId, eventId);
        // Send the delete request to the server
        $.ajax({
            url: base_url + "event_potluck/delete-category/", // URL for the delete route
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                category_id: categoryId,
                event_id: eventId,
            },
            success: function (response) {
                location.reload();
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
                alert("There was an error deleting the category.");
            },
        });
    });
    $('button[data-bs-toggle="modal"]').on("click", function () {
        const categoryId = $(this).data("category-id");
        $("#hiddenCategoryId").val(categoryId);
        const categoryname = $(this).data("category-name");
        $("#maindishesLabel").text(categoryname);
    });
    $("#saveCategoryBtn").on("click", function () {
        const categoryId = $("#hiddenCategoryId").val();
        const categoryName = $("#categoryName").val();
        const eventid = $("#event_id").val();
        const description = $("#text1").val();
        const self_bring_item =
            $('input[name="self_bring_item"]:checked').length > 0 ? 1 : 0;
        const quantity = $('input[name="sub_quantity"]').val();
        console.log(eventid, description, self_bring_item, quantity);

        $.ajax({
            url: base_url + "event_potluck/add-potluck-category-item", // Update with your URL
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                category_id: categoryId,
                category_name: categoryName,
                event_id: eventid,
                description: description,
                self_bring_item: self_bring_item,
                quantity: quantity,
            },
            success: function (response) {
                if (response.data) {
                    console.log(response.data);
                    // Close the modal
                    $("#categoryModal").modal("hide");

                    console.log(response.data);
                    window.location.href = "";
                    // Append the new accordion item into the correct container
                    const categoryList = $(
                        'div[data-category-id="' + categoryId + '"] .accordion'
                    );
                    categoryList.append(response.data);

                    // window.location.reload();
                    // Reset the form values
                    $("#categoryName").val("");
                    $("#text1").val("");
                    $('input[name="self_bring_item"]').prop("checked", false);
                    $('input[name="sub_quantity"]').val("");
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                alert("An error occurred: " + error);
            },
        });
    });
    // $('.itemQty').each(function () {
    //     const itemId = $(this).data('item-id');
    //     const quantity = $(this).data('max');
    //     const spokenQuantity = $(this).data('spoken-quantity');

    //     // alert(spokenQuantity);
    //     // Compare spoken_quantity and quantity
    //     if (spokenQuantity == quantity) {
    //         // Show success icon and hide danger icon
    //         $('#success_' + itemId).removeClass('d-none');
    //         $('#danger_' + itemId).addClass('d-none');
    //     } else {
    //         // Show danger icon and hide success icon
    //         $('#success_' + itemId).addClass('d-none');
    //         $('#danger_' + itemId).removeClass('d-none');
    //     }
    // });
});
// function updateQuantityStatusOnLoad() {
//     $('.category-list').each(function () {
//         const categoryList = $(this);
//         const totalQuantity = categoryList.data('total-quantity');
//         // alert(totalQuantity);
//         let spokenQuantity = 0;

//         // Sum all spoken quantities in this category
//         $('.itemQty').each(function () {
//             const spokenQuantity = $(this).data('spoken-quantity');
//             spokenQuantity += parseInt($(this).val(), 10) || 0;
//         });

//         // Update missing quantity
//         const missingQuantity = Math.max(0, totalQuantity - spokenQuantity);
//         categoryList.find('.missing-quantity').text(`${missingQuantity} Missing`);

//         // Update the color and icons based on the missing quantity
//         if (missingQuantity === 0) {
//             // No missing items, show the success icon
//             categoryList.find('.missing-quantity').css('color', 'green');
//             categoryList.find('.success-icon').removeClass('d-none');
//             categoryList.find('.danger-icon').addClass('d-none');
//         } else {
//             // Missing items, show the danger icon
//             categoryList.find('.missing-quantity').css('color', 'red');
//             categoryList.find('.danger-icon').removeClass('d-none');
//             categoryList.find('.success-icon').addClass('d-none');
//         }

//         // Update over-quantity display
//         const overQuantity = spokenQuantity - totalQuantity;
//         const overQuantityElement = categoryList.find('.over-quantity');
//         overQuantityElement.text(`${Math.max(0, overQuantity)} Item Over`);

//         // Show or hide the over-quantity element based on the calculation
//         if (overQuantity > 0) {
//             overQuantityElement.removeClass('d-none'); // Show the over-quantity element
//             categoryList.find('.success-icon').removeClass('d-none'); // Show the success (green SVG) element
//         } else {
//             overQuantityElement.addClass('d-none'); // Hide the over-quantity element
//             categoryList.find('.success-icon').addClass('d-none');  // Hide the success (green SVG) element
//         }
//     });
// }
// $(document).ready(function () {
//     updateQuantityStatusOnLoad();
// });

$(document).on("click", ".plus", function () {
    const container = $(this).closest(".qty-container");
    const input = container.find(".itemQty");
    let currentValue = parseInt(input.val(), 10) || 0;
    const category_id = $(this).data("category-id");
    const categoryKey = $(this).data("categorykey");

    const item_id = $(this).data("item-id");

    // Increment the quantity
    input.val(currentValue + 1).trigger("change");

    // // Optional: Update associated UI elements

    $("#newQuantity_" + item_id).val(currentValue + 1);

    const maxQuantity = input.data("max");
    if (maxQuantity) {
        const devideCount = container
            .closest(".accordion-item")
            .find("#quantity-display");
        devideCount.text(`${currentValue + 1}/${maxQuantity}`);
    }
    const categoryList = container.closest(".category-list");
    const totalQuantity = categoryList.data("total-quantity"); // Store total in data attribute
    let spokenQuantity = 0;

    // Sum all spoken quantities in this category
    categoryList.find(".itemQty").each(function () {
        spokenQuantity += parseInt($(this).val(), 10) || 0;
    });

    // Update missing quantity
    const missingQuantity = Math.max(0, totalQuantity - spokenQuantity);
    categoryList.find(".missing-quantity").text(`${missingQuantity} Missing`);
    if (missingQuantity <= 0 || missingQuantity === 0) {
        // If there are no missing items (or excess items), show the success icon
        $("#success_" + category_id).removeClass("d-none");
        $("#danger_" + category_id).addClass("d-none"); // Hide the danger icon
        // Change the color of the missing quantity text to green
        categoryList.find(".missing-quantity").css("color", "green");
    } else {
        // If there are missing items, show the danger icon
        $("#danger_" + category_id).removeClass("d-none");
        $(".missing-quantity").addClass("active");
        categoryList.find(".missing-quantity").css("color", "red");
    }
    const overQuantity = spokenQuantity - totalQuantity; // Only show if this is greater than 0

    // Get the over-quantity element
    const overQuantityElement = categoryList.find(
        ".over-quantity-" + category_id
    );

    // Update the over quantity text
    const successIcon = categoryList.find("#success" + category_id);
    // Update the over quantity text
    overQuantityElement.text(`${Math.max(0, overQuantity)} Item Over`);

    // Show or hide the over quantity based on the calculation
    if (overQuantity > 0) {
        overQuantityElement.removeClass("d-none"); // Show the over-quantity element
        $("#success_" + category_id).removeClass("d-none"); // Show the success (green SVG) element
    } else {
        overQuantityElement.addClass("d-none"); // Hide the over-quantity element
        // $('#success_' + category_id).addClass('d-none');  // Hide the success (green SVG) element
    }
    updateTOP(categoryKey);
    // updateQuantityStatusOnLoad();
});

$(document).on("click", ".minus", function () {
    const container = $(this).closest(".qty-container");
    const input = container.find(".itemQty");
    let currentValue = parseInt(input.val(), 10) || 0;
    const category_id = $(this).data("category-id");
    const categoryKey = $(this).data("categorykey");

    const item_id = $(this).data("item-id");

    // Decrement the quantity, but not below 0
    const newValue = Math.max(0, currentValue - 1);
    input.val(newValue).trigger("change");
    $("#newQuantity_" + item_id).val(newValue);
    // Optional: Update associated UI elements
    const maxQuantity = input.data("max");
    if (maxQuantity) {
        const devideCount = container
            .closest(".accordion-item")
            .find(".devide-count");
        devideCount.text(`${newValue}/${maxQuantity}`);
    }
    const categoryList = container.closest(".category-list");
    const totalQuantity = categoryList.data("total-quantity"); // Store total in data attribute
    let spokenQuantity = 0;

    // Sum all spoken quantities in this category
    categoryList.find(".itemQty").each(function () {
        spokenQuantity += parseInt($(this).val(), 10) || 0;
    });

    // Update missing quantity
    const missingQuantity = Math.max(0, totalQuantity - spokenQuantity);
    categoryList.find(".missing-quantity").text(`${missingQuantity} Missing`);
    if (missingQuantity === 0 || missingQuantity <= 0) {
        // If there are no missing items (or excess items), show the success icon
        $("#success_" + category_id).removeClass("d-none");
        $("#danger_" + category_id).addClass("d-none"); // Hide the danger icon
        // Change the color of the missing quantity text to green
        categoryList.find(".missing-quantity").css("color", "green");
    } else {
        // If there are missing items, show the danger icon
        $("#danger_" + category_id).removeClass("d-none");
        $(".missing-quantity").addClass("active");
        $("#success_" + category_id).addClass("d-none");
        categoryList.find(".missing-quantity").css("color", "red");
    }
    const overQuantity = spokenQuantity - totalQuantity;
    const overQuantityElement = categoryList.find(
        ".over-quantity-" + category_id
    );

    // Update the over quantity text
    overQuantityElement.text(`${Math.max(0, overQuantity)} Item Over`);

    // Show or hide the over quantity based on the calculation
    if (overQuantity > 0) {
        overQuantityElement.removeClass("d-none"); // Show the over-quantity element
        $("#success_" + category_id).removeClass("d-none"); // Show the success (green SVG) element
    } else {
        overQuantityElement.addClass("d-none"); // Hide the over-quantity element
        // $('#success_' + category_id).addClass('d-none');  // Hide the success (green SVG) element
    }
    updateTOP(categoryKey);
    // updateQuantityStatusOnLoad();
});
$(document).on("click", ".saveItemBtn", function () {
    var categoryId = $(this).data("category-id");
    var categoryItemId = $(this).data("item-id");
    var item_quantity = $("#newQuantity_" + categoryItemId).val();
    const eventid = $("#event_id").val();
    // console.log("cat"+category_id+' '+"item_id"+item_id+' '+"qty"+item_quantity);

    // const categoryItemId = $('#category_item_id').val();
    // const categoryId = $('#category_id').val();
    //
    // const quantity = $('#newQuantity').val();
    //    alert(quantity);
    $.ajax({
        url: base_url + "event_potluck/editUserPotluckItem", // Update with your URL
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            category_item_id: categoryItemId,

            event_id: eventid,
            category_id: categoryId,
            quantity: item_quantity,
        },
        success: function (response) {
            if (response.success) {
                //   alert('Quantity saved successfully');
            }
        },
        error: function (xhr, status, error) {
            alert("An error occurred: " + error);
        },
    });
});
$(document).on("change", ".itemQty", function () {
    const input = $(this);
    const itemId = $(this).data("item-id");
    console.log(itemId);
    const currentValue = parseInt(input.val(), 10) || 0;
    const maxQuantity = input.data("max");

    // Show success icon and hide danger icon if current value equals max quantity
    if (currentValue >= maxQuantity) {
        // Show success icon and hide danger icon
        $("#success_" + itemId).removeClass("d-none"); // Display the success icon
        $("#danger_" + itemId).addClass("d-none"); // Hide the danger icon
    } else {
        // Show danger icon and hide success icon
        $("#success_" + itemId).addClass("d-none"); // Hide the success icon
        $("#danger_" + itemId).removeClass("d-none"); // Display the danger icon
    }
});

$(document).on("click", ".plus_icon_user", function () {
    const categoryId = $(this).data("category-id");
    const itemId = $(this).data("item-id");
    const eventId = $(this).data("event-id");
    const categorykey = $(this).data("categorykey");
    const quantity = $(this).data("max");
    const userProfile = $(this).data("user-profile");
    const loginUserId = $(this).data("login-user-id");

    // Perform an AJAX call to fetch user details based on itemId and categoryId
    $.ajax({
        url: base_url + "event_potluck/fetch-user", // Your endpoint to fetch the user data
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            category_id: categoryId,
            item_id: itemId,
            user_profile: userProfile,
            login_user_id: loginUserId,
            quantity: quantity,
            event_id: eventId,
            categorykey:categorykey
        },
        success: function (response) {
            // On successful response, append the user data to the container
            const userContainer = $("#user-container-" + itemId);
            const userName = response.userName; // Assuming the response contains a 'userName'
            const maxQuantity = response.maxQuantity; // Assuming the response contains a 'maxQuantity'

            // const container = $(this).closest('.qty-container');
            // const input = container.find('.itemQty');
            //             const devideCount = container.closest('.accordion-item').find('#quantity-display');
            // devideCount.text(`${response.spoken_for}/${ item_quantity}`);
            if (response.status == "success") {
                userContainer.append(response.data);
            }
        },
        error: function (xhr, status, error) {
            console.log("Error: " + error);
        },
    });
});
$(document).on("click", ".according_toggel", function () {
    // Get the data attributes of the clicked element
    const categoryId = $(this).data("category-id");
    const itemId = $(this).data("item-id");

    // Find the target accordion panel using the data attributes
    const targetPanel = $(
        `.accordion-collapse[data-category-id="${categoryId}"][data-item-id="${itemId}"]`
    );

    // Check if the target panel exists
    if (targetPanel.length) {
        // Close all other accordion panels
        $(".accordion-collapse").collapse("hide");

        // Open the target panel
        targetPanel.collapse("show");
    } else {
        console.error("Subcategory panel not found for the selected item.");
    }
});

// $(document).on('click', '.deleteBtn', function () {

//     var categoryId = $(this).data('category-id');
//     var categoryItemId = $(this).data('item-id');
//     var item_quantity = $("#newQuantity_" + categoryItemId).val();
//     const eventid = $('#event_id').val();
//     // console.log("cat"+category_id+' '+"item_id"+item_id+' '+"qty"+item_quantity);

//     // const categoryItemId = $('#category_item_id').val();
//     // const categoryId = $('#category_id').val();
//     //
//     // const quantity = $('#newQuantity').val();
//     //    alert(quantity);
//     $.ajax({
//         url: base_url + "event_potluck/deleteUserPotluckItem/", // Update with your URL
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         data: {
//             category_item_id: categoryItemId,

//             event_id: eventid,
//             category_id: categoryId,
//             quantity: item_quantity
//         },
//         success: function (response) {
//             if (response.success) {
//                 //   alert('Quantity saved successfully');

//                 const devideCount = container.closest('.accordion-item').find('#quantity-display');
//                 devideCount.text(`${response.spoken_for}/${ item_quantity}`);

//                 // Optionally display a success message
//                 alert(response.message);
//             }
//         },
//         error: function (xhr, status, error) {
//             alert('An error occurred: ' + error);
//         }
//     });
// });

$(document).on("click", ".deleteBtn", function () {
    var categoryId = $(this).data("category-id");
    var categoryItemId = $(this).data("item-id");
    var item_quantity = $("#newQuantity_" + categoryItemId).val();
    const eventid = $("#event_id").val();

    $.ajax({
        url: base_url + "event_potluck/deleteUserPotluckItem", // Update with your URL
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            category_item_id: categoryItemId,
            event_id: eventid,
            category_id: categoryId,
            quantity: item_quantity,
        },
        success: function (response) {
            if (response.success) {
                // Remove the appended user data from the container
                $("#user-container-" + categoryItemId).empty(); // Removes all HTML inside the container

                // Update the quantity display
                const devideCount = $(this)
                    .closest(".accordion-item")
                    .find("#quantity-display");
                devideCount.text(`${response.spoken_for}/${item_quantity}`);

                // Optionally, show a success message
                // alert(response.message);

                // Redirect back (if required)
                window.location.href =
                    response.redirect_url || window.location.href; // You can specify a URL in the backend to redirect to after successful update
            }
        },
        error: function (xhr, status, error) {
            alert("An error occurred: " + error);
        },
    });
});

function updateTOP(categoryIndex) {
    var list = document.getElementsByClassName("list-slide-" + categoryIndex);

    if (list.length === 0) return;

    var accordions = list[0].getElementsByClassName("accordion-item");
    var totalItems = accordions.length;

    let totalMissing = 0;
    let totalOver = 0;

    for (let i = 0; i < totalItems; i++) {
        let categoryItem = accordions[i];

        // Get the required quantity
        let requiredQtyInput = categoryItem.querySelector(
            ".category-item-quantity"
        );
        let requiredQty = requiredQtyInput
            ? parseInt(requiredQtyInput.value)
            : 0;

        // Get the current user input quantity
        let inputQtyInput = categoryItem.querySelector(".input-qty");
        let inputQty = inputQtyInput ? parseInt(inputQtyInput.value) : 0;
        console.log({ inputQty });
        let innerUserQnt = $(`.innerUserQnt-${i}-${categoryIndex}`).val() || 0;
        console.log({ innerUserQnt });
        if (innerUserQnt && parseInt(innerUserQnt) >= 0) {
            inputQty = inputQty + parseInt(innerUserQnt);
        }
        console.log({ inputQty });

        if (inputQty < requiredQty) {
            totalMissing += requiredQty - inputQty;
        } else if (inputQty > requiredQty) {
            totalOver += inputQty - requiredQty;
        }
    }
    $("#missing-category-" + categoryIndex).text(totalMissing);
    $("#extra-category-" + categoryIndex).text(totalOver);
    if (totalMissing == 0) {
        // if (response == 0) {
        var svg =
            '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.00016 0.333984C3.32683 0.333984 0.333496 3.32732 0.333496 7.00065C0.333496 10.674 3.32683 13.6673 7.00016 13.6673C10.6735 13.6673 13.6668 10.674 13.6668 7.00065C13.6668 3.32732 10.6735 0.333984 7.00016 0.333984ZM10.1868 5.46732L6.40683 9.24732C6.3135 9.34065 6.18683 9.39398 6.0535 9.39398C5.92016 9.39398 5.7935 9.34065 5.70016 9.24732L3.8135 7.36065C3.62016 7.16732 3.62016 6.84732 3.8135 6.65398C4.00683 6.46065 4.32683 6.46065 4.52016 6.65398L6.0535 8.18732L9.48016 4.76065C9.6735 4.56732 9.9935 4.56732 10.1868 4.76065C10.3802 4.95398 10.3802 5.26732 10.1868 5.46732Z" fill="#23AA26"></path></svg>';
        $(".missing-category-svg-" + categoryIndex).html(svg);
        $(".missing-category-h6-" + categoryIndex).css("color", "#34C05C");
    } else {
        var svg =
            '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z" fill="#F73C71" /></svg>';
        $(".missing-category-svg-" + categoryIndex).html(svg);
        $(".missing-category-h6-" + categoryIndex).css("color", "#E20B0B");
    }
    if (totalOver > 0) {
        console.log("");
        $(".extra-category-h6-" + categoryIndex).show();
    } else {
        $(".extra-category-h6-" + categoryIndex).hide();
    }

    console.log("Total Missing Items:", totalMissing);
    console.log("Total Over Items:", totalOver);

    return { totalMissing, totalOver };
}
