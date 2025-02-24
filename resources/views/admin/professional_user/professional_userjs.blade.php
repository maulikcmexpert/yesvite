{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script type="text/javascript">
    $(function() {


        // var table = $("#professional_users_table").DataTable({
        //     processing: true,
        //     serverSide: true,

        //     ajax: '{{URL::to("/admin/professional_users")}}',
        //     columns: [{
        //             data: "number",
        //             name: "number"
        //         },
        //         {
        //             data: "profile",
        //             name: "profile"
        //         },
        //         {
        //             data: "username",
        //             name: "username"
        //         },
        //         {
        //             data: "app_user",
        //             name: "app_user"
        //         },


        //     ],
        // });


        $("#addMoreCat").click(function() {

            var html = $("#AddHtml").html();

            $("#appendHtml").append(html);
        });

        $(document).on("click", ".remove", function() {
            $(this).parent().remove();
        });

        $(document).ready(function() {
            // Function to validate category names
            $('#cateAdd').click(function(event) {
                event.preventDefault();
                var isValid = true;
                $('.category_name').each(function() {
                    if ($(this).val().trim() === '') {
                        isValid = false;
                        // Show error message for empty category name
                        $(this).next('.text-danger').text('Please enter category');
                    } else {

                        var thatVal = $(this).val();
                        var that = $(this);
                        $.ajax({
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                    "content"
                                ),
                            },
                            dataType: 'Json',
                            type: "POST",
                            url: "{{URL::to('admin/category/check_category_is_exist')}}",
                            data: {
                                category_name: function() {
                                    return thatVal;
                                },

                            },
                            success: function(output) {
                                if (output == false) {
                                    isValid = false;
                                    that.next('.text-danger').text('category is duplicate');
                                } else {
                                    $("#categoryForm").submit();
                                }
                            }
                        });
                    }

                });




            });


        });

        $("#updateCatForm").validate({
            rules: {
                category_name: {
                    required: true,
                    remote: {
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        url: "{{URL::to('admin/category/check_category_is_exist')}}",
                        method: "POST",
                        data: {
                            category_name: function() {
                                return $("input[name='category_name']").val();
                            },
                            id: function() {
                                return $("input[name='id']").val();
                            },
                        },
                    },
                },

            },
            messages: {
                category_name: {
                    required: "Please enter category name",
                    remote: "Category name is duplicate",
                },

            },

        })


        $(document).on("click", ".delete_category", function(event) {
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
                                toastr.success("Category Deleted successfully !");
                            } else {
                                toastr.error("Category don't Deleted !");
                            }
                        },
                    });
                }
            });
        });


    });
</script>