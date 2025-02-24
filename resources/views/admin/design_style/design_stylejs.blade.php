<script type="text/javascript">
    $(function() {


        var table = $("#design_style_table").DataTable({
            processing: true,
            serverSide: true,

            ajax: '{{URL::to("/admin/design_style")}}',
            columns: [{
                    data: "number",
                    name: "number"
                },
                {
                    data: "design_name",
                    name: "design_name"
                },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: true,
                },
            ],
        });


        $("#addMoreCat").click(function() {

            var html = $("#AddHtml").html();

            $("#appendHtml").append(html);
        });

        $(document).on("click", ".remove", function() {
            $(this).parent().parent().remove();
        });

        $(document).ready(function() {

            $('#cateAdd').click(function(event) {
                event.preventDefault();

                var promises = [];

                $('.design_name').each(function() {
                    var that = $(this);
                    var thatVal = that.val().trim();

                    if (thatVal == '') {
                        that.next('.text-danger').text('Please enter design style');
                    } else {
                        var promise = new Promise(function(resolve, reject) {
                            $.ajax({
                                headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                                },
                                dataType: 'Json',
                                type: "POST",
                                url: "{{URL::to('admin/design_style/check_design_style_is_exist')}}",
                                data: {
                                    design_name: thatVal
                                },
                                success: function(output) {
                                    if (output == false) {
                                        that.next('.text-danger').text('Design style is duplicate');
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
                        console.log("Duplicate design style found");
                    } else if (results.includes(true)) {
                        // If all results are true, submit the form
                        console.log("No duplicate design style, submitting form");
                        $("#designStyleForm").submit();
                    }
                }).catch(function(error) {
                    console.error("Error occurred during AJAX request:", error);
                });
            });

        });

        $("#updateDesignStyleForm").validate({
            rules: {
                design_name: {
                    required: true,
                    remote: {
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        url: "{{URL::to('admin/design_style/check_design_style_is_exist')}}",
                        method: "POST",
                        data: {
                            design_name: function() {
                                return $("input[name='design_name']").val();
                            },
                            id: function() {
                                return $("input[name='id']").val();
                            },
                        },
                    },
                },

            },
            messages: {
                design_name: {
                    required: "Please enter design name",
                    remote: "Design name is duplicate",
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