<script type="text/javascript">
    $(function() {




        var table = $("#template_table").DataTable({

            processing: true,

            serverSide: true,



            ajax: '{{ URL::to(' / admin / create_template ') }}',

            columns: [{

                    data: "number",

                    name: "number"

                },

                {

                    data: "category_name",

                    name: "category_name"

                },
                //                 {

                // data: "subcategory_name",

                // name: "subcategory_name"

                // },

                {

                    data: "image",

                    name: "image"

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

            // Function to validate category names

            $('#templateAdd').click(function(event) {

                event.preventDefault();

                var isValid = true;

                var selectedValue = $("#design_id").val();



                if (selectedValue === '') {

                    $("#design_id").next('.text-danger').text('Please select design name');
                    return false;
                    // Show error message for empty category name
                } else {
                    $("#design_id").next('.text-danger').text("");

                }

                var promises = [];

                $('.image').each(function() {
                    var that = $(this);
                    var thatVal = that.val().trim();

                    if (thatVal == '') {
                        that.next('.text-danger').text('Please enter subcategory');
                    } else {
                        var promise = new Promise(function(resolve, reject) {
                            $.ajax({
                                headers: {
                                    "X-CSRF-TOKEN": $(
                                            'meta[name="csrf-token"]')
                                        .attr("content"),
                                },
                                dataType: 'Json',
                                type: "POST",
                                url: "{{ URL::to('admin/subcategory/check_subcategory_is_exist') }}",

                                data: {
                                    subcategory_name: thatVal
                                },
                                success: function(output) {
                                    if (output == false) {
                                        that.next('.text-danger')
                                            .text(
                                                'Subcategory is duplicate'
                                            );
                                        resolve(false);
                                    } else {
                                        that.next('.text-danger')
                                            .text('');
                                        resolve(true);
                                    }
                                },
                                error: function() {
                                    reject("Error occurred");
                                }
                            });
                        });
                        promises.push(promise);
                    }
                });



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



        // })





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