<script type="text/javascript">
    $(function() {


        var table = $("#events_table").DataTable({
            processing: true,
            serverSide: true,

            ajax: '{{URL::to("/admin/events")}}',
            columns: [{
                    data: "number",
                    name: "number"
                },
                {
                    data: "event_name",
                    name: "event_name"
                },
                {
                    data: "event_by",
                    name: "event_by"
                },
                {
                    data: "email",
                    name: "email"
                },
                {
                    data: "start_date",
                    name: "start_date"
                },

                {
                    data: "end_date",
                    name: "end_date"
                },
                {
                    data: "venue",
                    name: "venue"
                },
                {
                    data: "event_status",
                    name: "event_status"
                },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: true,
                }
            ],
        });



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


        $(document).on('change', '#event_status', function() {
            var eventDate = $("#eventDate").val();
            var status = $(this).val();

            var table = $('#events_table').DataTable();

            if ($.fn.DataTable.isDataTable('#events_table')) {
                table.destroy();

            }

            $("#events_table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ URL::to("/admin/events") }}',
                    data: function(d) {
                        d.filter = eventDate;
                        d.status = status;
                    }
                },
                columns: [{
                        data: "number",
                        name: "number"
                    },
                    {
                        data: "event_name",
                        name: "event_name"
                    },
                    {
                        data: "event_by",
                        name: "event_by"
                    },
                    {
                        data: "email",
                        name: "email"
                    },
                    {
                        data: "start_date",
                        name: "start_date"
                    },
                    {
                        data: "end_date",
                        name: "end_date"
                    },
                    {
                        data: "venue",
                        name: "venue"
                    },
                    {
                        data: "event_status",
                        name: "event_status"
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: true,
                    }
                ]
            });


        });
        $(document).on('change', '#eventDate', function() {
            var eventDate = $(this).val();
            var status = $('#event_status option:selected').val();

            var table = $('#events_table').DataTable();

            if ($.fn.DataTable.isDataTable('#events_table')) {
                table.destroy();

            }

            $("#events_table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ URL::to("/admin/events") }}',
                    data: function(d) {
                        d.filter = eventDate;
                        d.status = status;
                    }
                },
                columns: [{
                        data: "number",
                        name: "number"
                    },
                    {
                        data: "event_name",
                        name: "event_name"
                    },
                    {
                        data: "event_by",
                        name: "event_by"
                    },
                    {
                        data: "email",
                        name: "email"
                    },
                    {
                        data: "start_date",
                        name: "start_date"
                    },
                    {
                        data: "end_date",
                        name: "end_date"
                    },
                    {
                        data: "venue",
                        name: "venue"
                    },
                    {
                        data: "event_status",
                        name: "event_status"
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: true,
                    }
                ]
            });


        });

        $(document).on('change', '#event_type', function() {

            var event_type = $('#event_type option:selected').val();

            var table = $('#events_table').DataTable();

            if ($.fn.DataTable.isDataTable('#events_table')) {
                table.destroy();

            }

            $("#events_table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ URL::to("/admin/events") }}',
                    data: function(d) {
                        //    d.filter = eventDate;
                        d.event_type = event_type;
                    }
                },
                columns: [{
                        data: "number",
                        name: "number"
                    },
                    {
                        data: "event_name",
                        name: "event_name"
                    },
                    {
                        data: "event_by",
                        name: "event_by"
                    },
                    {
                        data: "email",
                        name: "email"
                    },
                    {
                        data: "start_date",
                        name: "start_date"
                    },
                    {
                        data: "end_date",
                        name: "end_date"
                    },
                    {
                        data: "venue",
                        name: "venue"
                    },
                    {
                        data: "event_status",
                        name: "event_status"
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: true,
                    }
                ]
            });


        });


    });


    var thatval = $("#event_id").val();
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                "content"
            ),
        },

        type: "POST",
        url: "{{URL::to('admin/events/get_invited_user_data')}}",
        data: {
            event_id: function() {
                return thatval;
            },

        },
        success: function(output) {
            if (output) {
                $("#invitedUsersList").html(output);
            } else {
                $("#invitedUsersList").html("No any guest invited");
            }
        }
    });
</script>