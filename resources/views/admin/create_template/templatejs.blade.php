<script type="text/javascript">
    var base_url = "{{ url('/') }}/";
    $(function() {

        var table = $("#template_table").DataTable({
            processing: true,
            serverSide: true,

            // ajax: '{{URL::to("/admin/create_template")}}',
            // columns: [{
            //         data: "number",
            //         name: "number"
            //     },
            //     {
            //         data: "category_name",
            //         name: "category_name"
            //     },

            //     {

            //         data: "image",
            //         name: "image"

            //     },
            //     {
            //         data: "action",
            //         name: "action",
            //         orderable: false,
            //         searchable: true,

            //     },
            // ],

            ajax: '{{ URL::to("/admin/create_template") }}',
            columns: [{

                    data: "number",

                    name: "number"

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
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token


                var category_id = $(this).val();

                const formData = new FormData();
                formData.append('category_id', category_id);

                // fetch(`/get_all_subcategory/${category_id}`, {
                //         // method: 'POST',
                //         headers: {
                //             // 'Content-Type': 'application/json', // Set content type to JSON
                //             'X-CSRF-TOKEN': csrfToken // Include CSRF token
                //         },
                //         body: formData // Set the body to formData

                //     })
                //     .then(response => response.json())
                //     .then(data => {
                //         console.log('Text data saved successfully', data);
                //         // window.location.href = "{{URL::to('/admin/create_template')}}";

                //     })
                //     .catch((error) => {
                //         console.error('Error:', error);
                //     });

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
                        $('#event_design_sub_category_id').empty();
                        $('#event_design_sub_category_id').append('<option value="">Select subcategory</option>');
                        output.forEach(function(subcategory) {
                            $('#event_design_sub_category_id').append(
                                '<option value="' + subcategory.sub_category_id + '">' + subcategory.sub_category_name + '</option>'
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
                var selectedValue = $("#event_design_category_id").val();
                var selectedSubCategory = $("#event_design_sub_category_id").val();
                var image = $("#image").val();
                var filledimage = $("#filled_image").val();

                var hasError = false;
                if (selectedValue === '') {
                    $("#event_design_category_id").next('.text-danger').text('Please select design category');
                    hasError = true;
                }
                if (selectedSubCategory === '') {
                    $("#event_design_sub_category_id").next('.text-danger').text('Please select design subcategory');
                    hasError = true;
                }
                if (image === '') {
                    $("#image").next('.text-danger').text('Please upload Template');
                    hasError = true;
                } else {
                    $("#image").next('.text-danger').text("");
                }


                if (filledimage === '') {
                    $("#filled_image").next('.text-danger').text('Please upload Filled Template');
                    hasError = true;
                } else {
                    $("#filled_image").next('.text-danger').text("");
                }


                if (!hasError) {
                    $("#templateForm").submit();
                } else {
                    e.preventDefault();
                }

                // var promises = [];

                // $('.image').each(function() {
                //     var that = $(this);
                //     var thatVal = that.val().trim();

                //     if (thatVal == '') {
                //         that.next('.text-danger').text('Please enter subcategory');
                //     } else {
                //         var promise = new Promise(function(resolve, reject) {
                //             $.ajax({
                //                 headers: {
                //                     "X-CSRF-TOKEN": $(
                //                             'meta[name="csrf-token"]')
                //                         .attr("content"),
                //                 },
                //                 dataType: 'Json',
                //                 type: "POST",
                //                 url: "{{ URL::to('admin/subcategory/check_subcategory_is_exist') }}",

                //                 data: {
                //                     subcategory_name: thatVal
                //                 },
                //                 success: function(output) {
                //                     if (output == false) {
                //                         that.next('.text-danger')
                //                             .text(
                //                                 'Subcategory is duplicate'
                //                             );
                //                         resolve(false);
                //                     } else {
                //                         that.next('.text-danger')
                //                             .text('');
                //                         resolve(true);
                //                     }
                //                 },
                //                 error: function() {
                //                     reject("Error occurred");
                //                 }
                //             });
                //         });
                //         promises.push(promise);
                //     }
                // });



            });





        });



        // $("#updateSubCatForm").validate({

        //     rules: {

        //         event_design_category_id: {

        //             required: true,

        //         },

        //         subcategory_name: {

        //             required: true,
        //             remote: {

        //                 headers: {

        //                     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(

        //                         "content"

        //                     ),

        //                 },

        //                 url: "{{ URL::to('admin/subcategory/check_subcategory_is_exist') }}",

        //                 method: "POST",

        //                 data: {

        //                     subcategory_name: function() {

        //                         return $("input[name='subcategory_name']").val();

        //                     },

        //                     id: function() {

        //                         return $("input[name='id']").val();

        //                     },

        //                 },

        //             }

        //         }



        //     },

        //     messages: {

        //         event_design_category_id: {
        //             required: "Please select category",
        //         },
        //         subcategory_name: {

        //             required: "Please enter subcategory name",

        //             remote: "Subcategory name is duplicate"

        //         },



        //     },


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


        $(document).on("click", ".delete_template", function(event) {

            var userURL = $(this).data("url");

            event.preventDefault();

            swal({

                title: `Are you sure you want to delete this record?`,

                text: "If you delete this, it will be gone forever.",

                icon: "warning",

                buttons: true,

                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    $.ajax({

                        headers: {

                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(

                                "content"

                            ),

                        },

                        method: "DELETE",

                        url: userURL,

                        dataType: "json",

                        success: function(output) {

                            if (output == true) {

                                table.ajax.reload();

                                toastr.success("template Deleted successfully !");

                            } else {

                                toastr.error("template don't Deleted !");

                            }

                        },

                    });

                }

            });

        });





    });
</script>