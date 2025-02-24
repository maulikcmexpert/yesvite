<script type="text/javascript">
    $(function() {


        var table = $("#event_type_table").DataTable({
            processing: true,
            serverSide: true,

            ajax: '{{URL::to("/admin/event_type")}}',
            columns: [{
                    data: "number",
                    name: "number"
                },
                {
                    data: "event_type",
                    name: "event_type"
                },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: true,
                },
            ],
        });


        $("#addMoreEventType").click(function() {

            var html = $("#AddHtml").html();

            $("#appendHtml").append(html);
        });

        $(document).on("click", ".remove", function() {
            $(this).parent().parent().remove();
        });

        $(document).ready(function() {
            // Function to validate category names


            $('#EventTypeAdd').click(function(event) {
                event.preventDefault();

                var promises = [];

                $('.event_type').each(function() {
                    var that = $(this);
                    var thatVal = that.val().trim();

                    if (thatVal == '') {
                        that.next('.text-danger').text('Please enter event type');
                    } else {
                        var promise = new Promise(function(resolve, reject) {
                            $.ajax({
                                headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                                },
                                dataType: 'Json',
                                type: "POST",
                                url: "{{URL::to('admin/event_type/check_event_type_is_exist')}}",
                                data: {
                                    event_type: thatVal
                                },
                                success: function(output) {
                                    if (output == false) {
                                        that.next('.text-danger').text('Event type is duplicate');
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
                        console.log("Duplicate event type found");
                    } else if (results.includes(true)) {
                        // If all results are true, submit the form
                        console.log("No duplicate event types, submitting form");
                        $("#eventTypeForm").submit();
                    }
                }).catch(function(error) {
                    console.error("Error occurred during AJAX request:", error);
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


        // $(document).on("click", ".delete_category", function(event) {
        //     var userURL = $(this).data("url");
        //     event.preventDefault();
        //     swal({
        //         title: `Are you sure you want to delete this record?`,
        //         text: "If you delete this, it will be gone forever.",
        //         icon: "warning",
        //         buttons: true,
        //         dangerMode: true,
        //     }).then((willDelete) => {
        //         if (willDelete) {
        //             $.ajax({
        //                 headers: {
        //                     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
        //                         "content"
        //                     ),
        //                 },
        //                 method: "DELETE",
        //                 url: userURL,
        //                 dataType: "json",
        //                 success: function(output) {
        //                     if (output == true) {
        //                         table.ajax.reload();
        //                         toastr.success("Category Deleted successfully !");
        //                     } else {
        //                         toastr.error("Category don't Deleted !");
        //                     }
        //                 },
        //             });
        //         }
        //     });
        // });
        $(document).on('click', '.delete_event_type', function() {
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
                $('#delete_event_type_form').submit();

            }
        });
    })

    });
</script>