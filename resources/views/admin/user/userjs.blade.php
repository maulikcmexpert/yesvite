<script type="text/javascript">
    $(function() {


        // var table = $("#users_table").DataTable({
        //     processing: true,
        //     serverSide: true,

        //     ajax: '{{URL::to("/admin/users")}}',
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
        //         // {
        //         //     data: "action",
        //         //     name: "action",
        //         //     orderable: false,
        //         //     searchable: true,
        //         // },
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
            $('select[name="user-table_length"]').val(10);

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

        var base_url = $("#base_url").val();

        $(document).on('click', '#UserAdd', function(e) {
            e.preventDefault();
            if ($('#addUser_form').valid()) {
                $(this).prop('disabled', true); 
                $('#addUser_form').submit();
            }
});

        $('#addUser_form').validate({

            rules: {

                firstname: {
                    required: true
                },
                middlename: {
                    required: true
                },
                lastname: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        url: "{{URL::to('admin/user/check_new_contactemail')}}",
                        type: "POST",
                        data: {
                            email: function() {
                                return $(".email").val();
                            },

                        },
                    },

                },
                phone_number: {
                    // required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 15,
                    remote: {
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        url: "{{URL::to('admin/user/check_new_contactnumber')}}",
                        type: "POST",
                        data: {
                            phone_number: function() {
                                return $(".phone_number").val();
                            },
                            id: function() {
                                return $("#edit_id").val();
                            },
                        },
                    },
                }

            },
            messages: {


                firstname: {
                    required: "Please Enter First Name"
                },
                middlename: {
                    required: "Please Enter Middle Name"

                },
                lastname: {
                    required: "Please Enter Last Name"
                },
                email: {
                    required: "Please Enter Email",
                    email: "Please Enter a Valid Email",
                    remote: "Email is already exsits",

                },
                phone_number: {
                    // required: "Please Enter Mobile Number",
                    required: "Please enter a Phone Number",
                    digits: "Please enter a valid Phone Number",
                    minlength: "Phone Number must be minimum 10 digit",
                    maxlength: "Phone Number must be maxmimum 15 digit",
                    remote: "Phone Number is already exsits",
                }
            }

        });


        $('#EditUserForm').validate({
                    rules: {

                        firstname: {
                            required: true
                        },
                        middlename: {
                            required: true
                        },
                        lastname: {
                            required: true
                        },
                        email: {
                            required: true,
                            email: true,
                            remote: {
                                headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                        "content"
                                    ),
                                },
                                url: "{{URL::to('admin/user/CheckExistingUserEmail')}}",
                                type: "POST",
                                data: {
                                    email: function() {
                                        return $("#email").val();
                                    },
                                    id: function() {
                                        return $("#user-id").val(); // Adjust selector to match your ID field
                                    }
                                },
                            },

                        },
                        // phone_number: {
                        //     // required: true,
                        //     digits: true,
                        //     minlength: 10,
                        //     maxlength: 15,
                        //     remote: {
                        //         headers: {
                        //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        //                 "content"
                        //             ),
                        //         },
                        //         url: "{{URL::to('admin/user/check_new_contactnumber')}}",
                        //         type: "POST",
                        //         data: {
                        //             phone_number: function() {
                        //                 return $(".phone_number").val();
                        //             },
                        //             id: function() {
                        //                 return $("#edit_id").val();
                        //             },
                        //         },
                        //     },
                        // }

                    },
                    messages: {


                        firstname: {
                            required: "Please Enter First Name"
                        },
                        middlename: {
                            required: "Please Enter Middle Name"

                        },
                        lastname: {
                            required: "Please Enter Last Name"
                        },
                        email: {
                            required: "Please Enter Email",
                            email: "Please Enter a Valid Email",
                            remote: "Email is already exsits",

                        },
                        // phone_number: {
                        //     // required: "Please Enter Mobile Number",
                        //     required: "Please enter a Phone Number",
                        //     digits: "Please enter a valid Phone Number",
                        //     minlength: "Phone Number must be minimum 10 digit",
                        //     maxlength: "Phone Number must be maxmimum 15 digit",
                        //     remote: "Phone Number is already exsits",
                        // }
                    }
        });


        $('#TempPasswordForm').validate({
                    rules: {
                        password:{
                            required:true,
                            minlength:8,
                            maxlength:20,
                        }
                    },
                    messages: {
                            required:"Please enter Password",
                            minlength:"The password must be at least 6 characters long",
                            maxlength:"The password must not exceed 20 characters",
                    }
        });
        $(document).on('click', '.dropdown-item.block-user, .dropdown-item.unblock-user', function(e) {
            e.preventDefault();

            var userId = $(this).data('id'); // Get user ID
            var $button = $(this).closest('.dropdown').find(
            '.dropdown-toggle'); // The button to update text
            var isBlockAction = $(this).hasClass('block-user'); // Check if the action is to block

            $.ajax({
                url: "{{ route('update.status') }}", // Your route for updating the status
                type: "POST",
                data: {
                    id: userId,
                    _token: "{{ csrf_token() }}" // Ensure you send the CSRF token
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Determine new button text and dropdown item text based on action
                        var newButtonText = isBlockAction ? 'Block' : 'Unblock';
                        var newDropdownText = isBlockAction ? 'Unblock' : 'Block';

                        // Update the button text
                        $button.text(newButtonText);

                        // Update the dropdown item's text and class
                        var $dropdownItem = $(this);
                        if (isBlockAction) {
                            $dropdownItem.text(newDropdownText).removeClass('block-user')
                                .addClass('unblock-user');
                        } else {
                            $dropdownItem.text(newDropdownText).removeClass('unblock-user')
                                .addClass('block-user');
                        }
                    } else {
                        alert(response.message);
                    }
                }.bind(this), // Bind 'this' to ensure the correct context
                error: function() {
                    alert('An error occurred while updating the status.');
                }
            });
        });

    });
</script>