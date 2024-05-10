<script type="text/javascript">
    $(function() {





        var table = $("#subcategory_table").DataTable({

            processing: true,

            serverSide: true,



            ajax: '{{URL::to("/admin/subcategory")}}',

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

                    data: "action",

                    name: "action",

                    orderable: false,

                    searchable: true,

                },

            ],

        });





        $("#addMoreSubCat").click(function() {



            var html = $("#AddHtml").html();



            $("#appendHtml").append(html);

        });



        $(document).on("click", ".remove", function() {

            $(this).parent().remove();

        });



        $(document).ready(function() {

            // Function to validate category names

            $('#subCateAdd').click(function(event) {

                event.preventDefault();

                var isValid = true;

                var selectedValue = $("#event_design_category_id").val();



                if (selectedValue === '') {

                    $("#event_design_category_id").next('.text-danger').text('Please select subcategory');
                    return false;
                    // Show error message for empty category name
                } else {
                    $("#event_design_category_id").next('.text-danger').text("");

                }

                var promises = [];

                $('.subcategory_name').each(function() {
                    var that = $(this);
                    var thatVal = that.val().trim();

                    if (thatVal == '') {
                        that.next('.text-danger').text('Please enter subcategory');
                    } else {
                        var promise = new Promise(function(resolve, reject) {
                            $.ajax({
                                headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                                },
                                dataType: 'Json',
                                type: "POST",
                                url: "{{URL::to('admin/subcategory/check_subcategory_is_exist')}}",

                                data: {
                                    subcategory_name: thatVal
                                },
                                success: function(output) {
                                    if (output == false) {
                                        that.next('.text-danger').text('Subcategory is duplicate');
                                        resolve(false);
                                    } else {
                                        that.next('.text-danger').text('');
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

                Promise.all(promises).then(function(results) {
                    if (results.includes(false)) {
                        // If any result is false, do not submit the form
                        console.log("Duplicate subcategory found");
                    } else if (results.includes(true)) {
                        // If all results are true, submit the form
                        console.log("No duplicate subcategory, submitting form");
                        $("#subCategoryForm").submit();
                    }
                }).catch(function(error) {
                    console.error("Error occurred during AJAX request:", error);
                });


            });





        });



        $("#updateSubCatForm").validate({

            rules: {

                event_design_category_id: {

                    required: true,

                },

                subcategory_name: {

                    required: true,
                    remote: {

                        headers: {

                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(

                                "content"

                            ),

                        },

                        url: "{{URL::to('admin/subcategory/check_subcategory_is_exist')}}",

                        method: "POST",

                        data: {

                            subcategory_name: function() {

                                return $("input[name='subcategory_name']").val();

                            },

                            id: function() {

                                return $("input[name='id']").val();

                            },

                        },

                    }

                }



            },

            messages: {

                event_design_category_id: {
                    required: "Please select category",
                },
                subcategory_name: {

                    required: "Please enter subcategory name",

                    remote: "Subcategory name is duplicate"

                },



            },



        })





        $(document).on("click", ".delete_subcategory", function(event) {

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

                                toastr.success("SubCategory Deleted successfully !");

                            } else {

                                toastr.error("SubCategory don't Deleted !");

                            }

                        },

                    });

                }

            });

        });





    });
</script>