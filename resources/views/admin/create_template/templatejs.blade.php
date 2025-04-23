<script type="text/javascript">
    var base_url = "{{ url('/') }}/";
    $(function() {

        setCheckboxSelectLabels();
        $('.checkboxes').hide();

        $('.toggle-next').click(function() {
            $(this).next('.checkboxes').slideToggle(400);
        });


        $('.ckkBox').change(function() {
            toggleCheckedAll(this);
            setCheckboxSelectLabels();
        });
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.subcategory_drp_down_wrapper').length) {
                $('.checkboxes').slideUp(400);
            }
        });

    });

    function setCheckboxSelectLabels(elem) {
        var wrappers = $('.wrapper');

        $.each(wrappers, function(key, wrapper) {
            var checkboxes = $(wrapper).find('.ckkBox');
            var label = $(wrapper).find('.checkboxes').attr('id');
            var prevText = '';

            var anyChecked = false;


            $.each(checkboxes, function(i, checkbox) {
                var button = $(wrapper).find('button');

                if ($(checkbox).prop('checked') == true) {
                    anyChecked = true;

                    var text = $(checkbox).next().html();
                    var btnText = prevText + text;
                    var numberOfChecked = $(wrapper).find('input.val:checkbox:checked').length;
                    if (numberOfChecked >= 4) {
                        btnText = numberOfChecked + ' ' + label + ' selected';
                    }
                    $(button).text(btnText);
                    prevText = btnText + ', ';
                }
            });
        });
    }

    function toggleCheckedAll(checkbox) {
        var apply = $(checkbox).closest('.wrapper').find('.apply-selection');
        apply.fadeIn('slow');

        var val = $(checkbox).closest('.checkboxes').find('.val');
        var all = $(checkbox).closest('.checkboxes').find('.all');
        var ckkBox = $(checkbox).closest('.checkboxes').find('.ckkBox');

        if (!$(ckkBox).is(':checked')) {
            $(all).prop('checked', true);
            return;
        }

        if ($(checkbox).hasClass('all')) {
            $(val).prop('checked', false);
        } else {
            $(all).prop('checked', false);
        }
    }

    // });
    $(function() {

        var table = $("#template_table").DataTable({
            processing: true,
            serverSide: true,

            pageLength: 50,
            lengthMenu: [
                [50, 100, -1],
                [50, 100, "All"]
            ],

            ajax: '{{ URL::to('/admin/create_template') }}',
            columns: [{

                    data: "number",

                    name: "number"

                },

                {

                    data: "created_by",

                    name: "created_by"

                },

                {

                    data: "created_by_email",

                    name: "created_by_email"

                },
                {

                    data: "category_name",

                    name: "category_name"

                },

                {

                    data: "subcategory_name",

                    name: "subcategory_name"

                },

                {

                    data: "image",

                    name: "image"

                },
                {

                    data: "filled_image",
                    name: "filled_image"

                },
                {

                    data: "create_time",
                    name: "create_time"

                },
                {

                    data: "last_edited",
                    name: "last_edited"

                },

                {

                    data: "show_template",

                    name: "show_template",

                    orderable: false,

                    searchable: true,

                },
                {

                    data: "action",

                    name: "action",

                    orderable: false,

                    searchable: true,

                },

            ],
        });

        $("#addMoreTemplate").click(function() {
            var html = $("#AddHtml").html(); // Get the hidden HTML
            $("#appendHtml").append(html); // Append the HTML to the form
        });

        $(document).on("click", ".remove", function() {
            $(this).closest('.col-lg-3')
                .remove(); // Remove the entire col-lg-3 div containing the input
        });

        $(document).ready(function() {
            $(document).on('change', '#event_design_category_id', function() {
                if ($(this).val() !== '') {
                    $(this).next('.text-danger').text("");
                }
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content'); // Get CSRF token
                var category_id = $(this).val();
                const formData = new FormData();
                formData.append('category_id', category_id);
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $(
                                'meta[name="csrf-token"]')
                            .attr("content"),
                    },
                    type: "POST",
                    url: "{{ URL::to('admin/create_template/get_all_subcategory') }}",

                    data: {
                        category_id: category_id
                    },
                    success: function(output) {
                        if (Array.isArray(output) && output.length === 0) {
                            $('#event_design_sub_category_id').empty();
                            // $('#event_design_sub_category_id').append('<option value="">No SubCategory Found</option>');
                            $('#event_design_sub_category_id').append(
                                '<label><span>No SubCategory Found</span> </label>'

                            );
                            return;
                        }
                        console.log(output);
                        $('#event_design_sub_category_id').empty();
                        // $('#event_design_sub_category_id').append('<option value="">Select subcategory</option>');
                        // $('#event_design_sub_category_id').append(
                        //         '<label><input type="checkbox" value="" class="ckkBox val" /><span>No SubCategory Found</span> </label><br>'

                        //     );
                        $('.select-subcat-btn').text('Select Subcategory');
                        output.forEach(function(subcategory) {
                            // $('#event_design_sub_category_id').append(
                            //     '<option value="' + subcategory.sub_category_id + '">' + subcategory.sub_category_name + '</option>'

                            // );
                            $('#event_design_sub_category_id').append(
                                '<label><input type="checkbox" value="' +
                                subcategory.sub_category_id +
                                '" class="ckkBox val" name="subcategory[]" /><span>' +
                                subcategory.sub_category_name +
                                '</span> </label>'

                            );
                        });
                       
                    },
                    error: function() {
                        reject("Error occurred");
                    }
                });
            });
            $(document).on('change', '#event_design_sub_category_id', function() {
                if ($(this).val() !== '') {
                    $(this).next('.text-danger').text("");
                }
            });
            $(document).on('change', '#image', function() {
                if ($(this).val() !== '') {
                    $(this).next('.text-danger').text("");
                }
            });
            $(document).on('change', '#filled_image', function() {
                if ($(this).val() !== '') {
                    $(this).next('.text-danger').text("");
                }
            });

            $(document).on('click', '#templateAdd', function(e) {
                // alert();
                var selectedValue = $("#event_design_category_id").val();
                //     var selectedSubCategory = $("#event_design_sub_category_id").val();
                var image = $("#image").val();
                var filledimage = $("#filled_image").val();

                var hasError = false;
                if (selectedValue === '') {
                    $("#event_design_category_id").next('.text-danger').text(
                        'Please select design category');
                    hasError = true;
                }
                //     if (selectedSubCategory === '') {
                //         $("#event_design_sub_category_id").next('.text-danger').text('Please select design subcategory');
                //         hasError = true;
                //     }
                if ($("input[name='subcategory[]']:checked").length === 0) {
                    // alert();
                    // e.preventDefault(); // Prevent form submission or action
                    $(".subcategory_error_bx").text('Please select design subcategory');
                    hasError = true;
                } else {
                    // hasError = false;

                }
                if (image === '') {
                    $("#image").next('.text-danger').text('Please upload Template');
                    hasError = true;
                } else {
                    $("#image").next('.text-danger').text("");
                }


                if (filledimage === '') {
                    $("#filled_image").next('.text-danger').text(
                        'Please upload Filled Template');
                    hasError = true;
                } else {
                    $("#filled_image").next('.text-danger').text("");
                }

                // alert(hasError);
                if (!hasError) {
                    $("#templateForm").submit();
                } else {
                    e.preventDefault();
                }

            });


            $(document).on('click', '#templateUpdate', function(e) {
                var selectedValue = $("#event_design_category_id").val();
                //     var selectedSubCategory = $("#event_design_sub_category_id").val();
                var image = $("#image").val();
                var filledimage = $("#filled_image").val();

                var hasError = false;
                if (selectedValue === '') {
                    $("#event_design_category_id").next('.text-danger').text(
                        'Please select design category');
                    hasError = true;
                }
                //     if (selectedSubCategory === '') {
                //         $("#event_design_sub_category_id").next('.text-danger').text('Please select design subcategory');
                //         hasError = true;
                //     }
                if ($("input[name='subcategory[]']:checked").length === 0) {
                    // alert();
                    // e.preventDefault(); // Prevent form submission or action
                    $(".subcategory_error_bx").text('Please select design subcategory');
                    hasError = true;
                } else {
                    $(".subcategory_error_bx").text('');

                }
                if (image === '') {
                    $("#image").next('.text-danger').text('Please upload Template');
                    hasError = true;
                } else {
                    $("#image").next('.text-danger').text("");
                }


                if (filledimage === '') {
                    $("#filled_image").next('.text-danger').text(
                        'Please upload Filled Template');
                    hasError = true;
                } else {
                    $("#filled_image").next('.text-danger').text("");
                }


                if (!hasError) {
                    $("#templateEditForm").submit();
                } else {
                    e.preventDefault();
                }

            });


        });



        $('#image').on('change', function() {
            previewImage(this, '#add_preview_image');
        });

        $('#filled_image').on('change', function() {
            previewImage(this, '#add_preview_filled_image');
        });

        $('#upload_image').on('change', function() {
            previewImage(this, '#preview_image');
        });

        $('#upload_filled_image').on('change', function() {
            previewImage(this, '#preview_filled_image');
        });




        function previewImage(inputElement, previewElement) {
            var file = inputElement.files[0];
            if (file) {
                // alert();
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(previewElement).css('display', 'block');
                    $(previewElement).attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        }

        $(document).on('click', '.delete_template', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // alert();
                    $('#delete_template_form' + id).submit();

                }
            });
        })

    });
    $(document).on("change", "#templateToggle", function() {
        let isVisible = $(this).is(":checked") ? 1 : 0;
        let template_id = $(this).attr('data-id');
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            type: "GET",
            url: "{{ route('show_template') }}",
            data: {
                isVisible: isVisible,
                template_id: template_id
            },
            success: function(response) {
                console.log("Success:", response);
                toastr.success('Status Updated Successfully');
            },
            error: function(xhr) {
                console.error("Error updating visibility:", xhr.responseText);
            }
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
        const tagsInput = document.getElementById('tags');

        tagsInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent form submission
                const tagValue = tagsInput.value.trim();

                if (tagValue) {
                    // Add the tag or handle the input logic here
                    console.log('Tag added:', tagValue);
                    tagsInput.value = ''; // Clear the input after adding the tag
                }
            }
        });
    });

    const designData = JSON.parse($('#designData').val());

//     // Convert your designData into flat suggestion list
// let suggestionList = [];

// designData.forEach(category => {
//     suggestionList.push({ value: category.name });
//     category.subcategories.forEach(sub => {
//         suggestionList.push({ value: sub.name });
//     });
// });

// // Initialize Tagify
// const input = document.querySelector('#tags');
// const tagify = new Tagify(input, {
//     whitelist: suggestionList.map(item => item.value),
//     dropdown: {
//         enabled: 1,
//         maxItems: 20,
//         position: "all",
//         closeOnSelect: true,
//         highlightFirst: true
//     }
// });

//     $(document).on("keyup", ".bootstrap-tagsinput input", function() {
//         const query = $(this).val().toLowerCase();
//         let results = '';

//         if (query.trim() !== '') {
//             designData.forEach(category => {
//                 if (category.name.toLowerCase().includes(query)) {
//                     results += `
//                     <div class="search-item category" data-category-id="${category.id}" data-name="${category.name}">
//                         ${category.name}
//                     </div>`;
//                 }
//                 category.subcategories.forEach(subcategory => {
//                     if (subcategory.name.toLowerCase().includes(query)) {
//                         results += `
//                         <div class="search-item subcategory" data-id="${subcategory.id}" data-category-id="${category.id}" data-name="${subcategory.name}">
//                             ${subcategory.name}
//                         </div>`;
//                     }
//                 });
//             });
//             if (results === '') {
//                 results = `<div class="search-item">No Data Found</div>`;
//             }
//         }
//         $('#filtered_results').html(results);
//     });
    
//     $(document).on('click', '#filtered_results .search-item', function() {
//             console.log('Suggestion clicked');
//             const $tagInputContainer = $('.bootstrap-tagsinput');
//             const $inputField = $tagInputContainer.find('input');
//             const selectedText = $(this).data('name');
//             $inputField.val(selectedText);
//             $inputField.trigger('blur');
//             $('#filtered_results').empty();
// });

</script>
