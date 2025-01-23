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
    $('#category').on('input', function () {
        var maxLength = 30;
        var currentLength = $(this).val().length;

        // If current length exceeds max length, truncate the value
        if (currentLength > maxLength) {
            $(this).val($(this).val().substring(0, maxLength));
            currentLength = maxLength; // Ensure count doesn't go over
        }

        // Update the character count
        $('#charCount').text(currentLength);
    });
    $('#addCategoryModal').on('click', function () {
        $('#categoryForm')[0].reset(); // Reset the form
        $('#charCount').text('0');    // Reset the character count display
    });
    $('#deletemodal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var categoryId = button.data('category-id');
        var eventId = button.data('event-id');


        $('#confirmDeleteCategory').data('category-id', categoryId).data('event-id', eventId);
    });
    $('[data-bs-toggle="modal"][data-bs-target="#editcategorymodal"]').on('click', function () {
        // Get data attributes from the clicked link
        const categoryId = $(this).data('category-id');
        const categoryName = $(this).data('category-name');
        const categoryQuantity = $(this).data('category-quantity');
        console.log(categoryId);
        console.log('Category Name:', categoryName);
        console.log('Category Quantity:', categoryQuantity);
        // Set the values in the modal form
        $('#categorys').val(categoryName); // Set category name in the input field
        $('#quantitys').val(categoryQuantity); // Set category quantity in the input field


        const formAction = `/event_potluck/updateCategory/${categoryId}) }}`;
        $('#categoryForms').attr('action', formAction);
    });
    // Debugging to check if the modal is shown and values are set
    $('#confirmDeleteCategory').on('click', function () {
        var categoryId = $(this).data('category-id');
        var eventId = $(this).data('event-id');
        console.log(categoryId, eventId);
        // Send the delete request to the server
        $.ajax({
            url: base_url + "event_potluck/delete-category/", // URL for the delete route
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                category_id: categoryId,
                event_id: eventId,

            },
            success: function (response) {
                location.reload();

            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('There was an error deleting the category.');
            }
        });
    });
    $('button[data-bs-toggle="modal"]').on('click', function () {
        const categoryId = $(this).data('category-id');
        $('#hiddenCategoryId').val(categoryId);
        const categoryname = $(this).data('category-name');
        $('#maindishesLabel').text(categoryname);
    });
    $('#saveCategoryBtn').on('click', function () {
        const categoryId = $('#hiddenCategoryId').val();
        const categoryName = $('#categoryName').val();
        const eventid = $('#event_id').val();
        const description = $('#text1').val();
        const self_bring_item = $('input[name="self_bring_item"]:checked').length > 0 ? 1 : 0;
        const quantity = $('input[name="sub_quantity"]').val();
        console.log(eventid, description, self_bring_item, quantity);

        $.ajax({
            url: base_url + "event_potluck/add-potluck-category-item/", // Update with your URL
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                category_id: categoryId,
                category_name: categoryName,
                event_id: eventid,
                description: description,
                self_bring_item: self_bring_item,
                quantity: quantity
            },
            success: function (response) {
                if (response.data) {
                    console.log(response.data);
                    // Close the modal
                    $('#categoryModal').modal('hide');

                    console.log(response.data);

                    // Append the new accordion item into the correct container
                    const categoryList = $('div[data-category-id="' + categoryId + '"] .accordion');
                    categoryList.append(response.data);


                    // Reset the form values
                    $('#categoryName').val('');
                    $('#text1').val('');
                    $('input[name="self_bring_item"]').prop('checked', false);
                    $('input[name="sub_quantity"]').val('');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                alert('An error occurred: ' + error);
            }
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

$(document).on('click', '.plus', function () {
    const container = $(this).closest('.qty-container');
    const input = container.find('.itemQty');
    let currentValue = parseInt(input.val(), 10) || 0;
    const category_id = $(this).data('category-id');
    const item_id = $(this).data('item-id');

    // Increment the quantity
    input.val(currentValue + 1).trigger('change');

    // // Optional: Update associated UI elements

    $('#newQuantity_' + item_id).val(currentValue + 1);

    const maxQuantity = input.data('max');
    if (maxQuantity) {
        const devideCount = container.closest('.accordion-item').find('#quantity-display');
        devideCount.text(`${currentValue + 1}/${maxQuantity}`);
    }
    const categoryList = container.closest('.category-list');
    const totalQuantity = categoryList.data('total-quantity'); // Store total in data attribute
    let spokenQuantity = 0;

    // Sum all spoken quantities in this category
    categoryList.find('.itemQty').each(function () {
        spokenQuantity += parseInt($(this).val(), 10) || 0;
    });

    // Update missing quantity
    const missingQuantity = Math.max(0, totalQuantity - spokenQuantity);
    categoryList.find('.missing-quantity').text(`${missingQuantity} Missing`);
    if (missingQuantity <= 0 || missingQuantity === 0) {
        // If there are no missing items (or excess items), show the success icon
        $('#success_' + category_id).removeClass('d-none');
        $('#danger_' + category_id).addClass('d-none'); // Hide the danger icon
        // Change the color of the missing quantity text to green
        categoryList.find('.missing-quantity').css('color', 'green');
    } else {
        // If there are missing items, show the danger icon
        $('#danger_' + category_id).removeClass('d-none');
        $('.missing-quantity').addClass('active');
        categoryList.find('.missing-quantity').css('color', 'red');
    }
    const overQuantity = spokenQuantity - totalQuantity; // Only show if this is greater than 0

    // Get the over-quantity element
    const overQuantityElement = categoryList.find('.over-quantity');

    // Update the over quantity text
    const successIcon = categoryList.find('#success' + category_id);
    // Update the over quantity text
    overQuantityElement.text(`${Math.max(0, overQuantity)} Item Over`);

    // Show or hide the over quantity based on the calculation
    if (overQuantity > 0) {
        overQuantityElement.removeClass('d-none'); // Show the over-quantity element
        $('#success_' + category_id).removeClass('d-none'); // Show the success (green SVG) element
    } else {
        overQuantityElement.addClass('d-none'); // Hide the over-quantity element
        $('#success_' + category_id).addClass('d-none');  // Hide the success (green SVG) element
    }
    // updateQuantityStatusOnLoad();
});

$(document).on('click', '.minus', function () {
    const container = $(this).closest('.qty-container');
    const input = container.find('.itemQty');
    let currentValue = parseInt(input.val(), 10) || 0;
    const category_id = $(this).data('category-id');
    const item_id = $(this).data('item-id');

    // Decrement the quantity, but not below 0
    const newValue = Math.max(0, currentValue - 1);
    input.val(newValue).trigger('change');
    $('#newQuantity_' + item_id).val(newValue);
    // Optional: Update associated UI elements
    const maxQuantity = input.data('max');
    if (maxQuantity) {
        const devideCount = container.closest('.accordion-item').find('.devide-count');
        devideCount.text(`${newValue}/${maxQuantity}`);
    }
    const categoryList = container.closest('.category-list');
    const totalQuantity = categoryList.data('total-quantity'); // Store total in data attribute
    let spokenQuantity = 0;

    // Sum all spoken quantities in this category
    categoryList.find('.itemQty').each(function () {
        spokenQuantity += parseInt($(this).val(), 10) || 0;
    });

    // Update missing quantity
    const missingQuantity = Math.max(0, totalQuantity - spokenQuantity);
    categoryList.find('.missing-quantity').text(`${missingQuantity} Missing`);
    if (missingQuantity === 0 || missingQuantity <= 0) {
        // If there are no missing items (or excess items), show the success icon
        $('#success_' + category_id).removeClass('d-none');
        $('#danger_' + category_id).addClass('d-none'); // Hide the danger icon
        // Change the color of the missing quantity text to green
        categoryList.find('.missing-quantity').css('color', 'green');
    } else {
        // If there are missing items, show the danger icon
        $('#danger_' + category_id).removeClass('d-none');
        $('.missing-quantity').addClass('active');
        categoryList.find('.missing-quantity').css('color', 'red');
    }
    const overQuantity = spokenQuantity - totalQuantity;
    const overQuantityElement = categoryList.find('.over-quantity');


    // Update the over quantity text
    overQuantityElement.text(`${Math.max(0, overQuantity)} Item Over`);

    // Show or hide the over quantity based on the calculation
    if (overQuantity > 0) {
        overQuantityElement.removeClass('d-none'); // Show the over-quantity element
        $('#success_' + category_id).removeClass('d-none'); // Show the success (green SVG) element
    } else {
        overQuantityElement.addClass('d-none'); // Hide the over-quantity element
        $('#success_' + category_id).addClass('d-none');  // Hide the success (green SVG) element
    }
    // updateQuantityStatusOnLoad();
});
$(document).on('click', '.saveItemBtn', function () {

    var categoryId = $(this).data('category-id');
    var categoryItemId = $(this).data('item-id');
    var item_quantity = $("#newQuantity_" + categoryItemId).val();
    const eventid = $('#event_id').val();
    // console.log("cat"+category_id+' '+"item_id"+item_id+' '+"qty"+item_quantity);

    // const categoryItemId = $('#category_item_id').val();
    // const categoryId = $('#category_id').val();
    //
    // const quantity = $('#newQuantity').val();
    //    alert(quantity);
    $.ajax({
        url: base_url + "event_potluck/editUserPotluckItem/", // Update with your URL
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            category_item_id: categoryItemId,

            event_id: eventid,
            category_id: categoryId,
            quantity: item_quantity
        },
        success: function (response) {
            if (response.success) {
                //   alert('Quantity saved successfully');
            }
        },
        error: function (xhr, status, error) {
            alert('An error occurred: ' + error);
        }
    });
});
$(document).on('change', '.itemQty', function () {
    const input = $(this);
    const itemId = $(this).data('item-id')
    console.log(itemId);
    const currentValue = parseInt(input.val(), 10) || 0;
    const maxQuantity = input.data('max');

    // Show success icon and hide danger icon if current value equals max quantity
    if (currentValue >= maxQuantity) {
        // Show success icon and hide danger icon
        $('#success_' + itemId).removeClass('d-none'); // Display the success icon
        $('#danger_' + itemId).addClass('d-none'); // Hide the danger icon
    } else {
        // Show danger icon and hide success icon
        $('#success_' + itemId).addClass('d-none'); // Hide the success icon
        $('#danger_' + itemId).removeClass('d-none'); // Display the danger icon
    }
});

$(document).on('click', '.plus_icon_user', function () {
    const categoryId = $(this).data('category-id');
    const itemId = $(this).data('item-id');
    const eventId = $(this).data('event-id');
    const quantity = $(this).data('max');
    const userProfile = $(this).data('user-profile');
    const loginUserId = $(this).data('login-user-id');

    // Perform an AJAX call to fetch user details based on itemId and categoryId
    $.ajax({
        url: base_url + 'event_potluck/fetch-user/',  // Your endpoint to fetch the user data
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            category_id: categoryId,
            item_id: itemId,
            user_profile: userProfile,
            login_user_id: loginUserId,
            quantity: quantity,
            event_id: eventId
        },
        success: function (response) {
            // On successful response, append the user data to the container
            const userContainer = $('#user-container-' + itemId);
            const userName = response.userName; // Assuming the response contains a 'userName'
            const maxQuantity = response.maxQuantity; // Assuming the response contains a 'maxQuantity'

            // const container = $(this).closest('.qty-container');
            // const input = container.find('.itemQty');
            //             const devideCount = container.closest('.accordion-item').find('#quantity-display');
            // devideCount.text(`${response.spoken_for}/${ item_quantity}`);

            userContainer.append(response.data);
        },
        error: function (xhr, status, error) {
            console.log("Error: " + error);
        }
    });
});
$(document).on('click', '.according_toggel', function () {
    // Get the data attributes of the clicked element
    const categoryId = $(this).data('category-id');
    const itemId = $(this).data('item-id');

    // Find the target accordion panel using the data attributes
    const targetPanel = $(`.accordion-collapse[data-category-id="${categoryId}"][data-item-id="${itemId}"]`);

    // Check if the target panel exists
    if (targetPanel.length) {
        // Close all other accordion panels
        $('.accordion-collapse').collapse('hide');

        // Open the target panel
        targetPanel.collapse('show');
    } else {
        console.error('Subcategory panel not found for the selected item.');
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


$(document).on('click', '.deleteBtn', function () {

    var categoryId = $(this).data('category-id');
    var categoryItemId = $(this).data('item-id');
    var item_quantity = $("#newQuantity_" + categoryItemId).val();
    const eventid = $('#event_id').val();

    $.ajax({
        url: base_url + "event_potluck/deleteUserPotluckItem/", // Update with your URL
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            category_item_id: categoryItemId,
            event_id: eventid,
            category_id: categoryId,
            quantity: item_quantity
        },
        success: function (response) {
            if (response.success) {
                // Remove the appended user data from the container
                $('#user-container-' + categoryItemId).empty();  // Removes all HTML inside the container

                // Update the quantity display
                const devideCount = $(this).closest('.accordion-item').find('#quantity-display');
                devideCount.text(`${response.spoken_for}/${item_quantity}`);

                // Optionally, show a success message
                alert(response.message);

                // Redirect back (if required)
                window.location.href = response.redirect_url || window.location.href;  // You can specify a URL in the backend to redirect to after successful update
            }
        },
        error: function (xhr, status, error) {
            alert('An error occurred: ' + error);
        }
    });
});
