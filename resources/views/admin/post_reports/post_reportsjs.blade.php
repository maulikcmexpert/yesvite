<script type="text/javascript">
    $(function() {

        $(document).on('click', '.DeleteReport_post', function() {

            var event_report_id = $(this).data('id');

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

                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"),
                        },
                        // dataType: 'Json',
                        type: "GET",
                        url: "{{ route('delete_post_report') }}",
                        data: {
                            event_report_id: event_report_id
                        },
                        success: function(output) {
                            if (output == true) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Your Reported Post have",
                                    icon: "success"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href ="{{URL::to('/admin/user_post_report')}}";
                                    }
                                });
                            }
                        },
                        error: function() {}
                    });


                }
            });
        })

        //         var event_report_id=$(this).data('id');

        //         $.ajax({
        //                             headers: {
        //                                 "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        //                             },
        //                             dataType: 'Json',
        //                             type: "GET",
        //                             url: "{{ URL::to('/admin/user_post_report/delete_post_report') }}",
        //                             data: {
        //                                 event_report_id: event_report_id
        //                             },
        //                             success: function(output) {
        //                                 console.log(output)
        //                             },
        //                             error: function() {
        //                             }
        //                         });
        // })
        // var table = $("#user_post_report_table").DataTable({
        //     processing: true,
        //     serverSide: true,

        //     ajax: '{{ URL::to('/admin/user_post_report') }}',
        //     columns: [{
        //             data: "number",
        //             name: "number"
        //         },

        //         {
        //             data: "username",
        //             name: "username"
        //         },
        //         {
        //             data: "event_name",
        //             name: "event_name"
        //         },
        //         {
        //             data: "post_type",
        //             name: "post_type"
        //         }, {
        //             data: "action",
        //             name: "action"
        //         }

        //     ],
        // });


    });
</script>
