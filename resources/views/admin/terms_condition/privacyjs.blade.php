<script type="text/javascript">
    $(function() {


        var table = $("#privacy_table").DataTable({
            processing: true,
            serverSide: true,

            ajax: '{{ URL::to('/admin/privacy_policy') }}',
            columns: [{
                    data: "number",
                    name: "number"
                },
                {
                    data: "title",
                    name: "title"
                },
                {
                    data: "description",
                    name: "description"
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

            // console.log($(this).parent().parent().parent().html());
            $(this).parent().parent().remove();
        });

        // $(document).ready(function() {
        //     // Function to validate category names
        //     $('#cateAdd').click(function(event) {
        //         event.preventDefault();

        //         var promises = [];

        //         $('.title').each(function() {
        //             var that = $(this);
        //             var thatVal = that.val().trim();

        //             if (thatVal == '') {
        //                 that.next('.text-danger').text('Please enter title');
        //             } else {
        //                 var promise = new Promise(function(resolve, reject) {
        //                     $.ajax({
        //                         headers: {
        //                             "X-CSRF-TOKEN": $(
        //                                     'meta[name="csrf-token"]')
        //                                 .attr("content"),
        //                         },
        //                         dataType: 'Json',
        //                         type: "POST",
        //                         url: "{{ URL::to('admin/category/check_category_is_exist') }}",
        //                         data: {
        //                             category_name: thatVal
        //                         },
        //                         success: function(output) {
        //                             if (output == false) {
        //                                 that.next('.text-danger')
        //                                     .text(
        //                                         'Category is duplicate'
        //                                         );
        //                                 resolve(false);
        //                             } else {
        //                                 that.next('.text-danger')
        //                                     .text('');
        //                                 resolve(true);
        //                             }
        //                         },
        //                         error: function() {
        //                             reject("Error occurred");
        //                         }
        //                     });
        //                 });
        //                 promises.push(promise);
        //             }
        //         });

        //         Promise.all(promises).then(function(results) {
        //             if (results.includes(false)) {
        //                 // If any result is false, do not submit the form
        //                 console.log("Duplicate category found");
        //             } else if (results.includes(true)) {
        //                 // If all results are true, submit the form
        //                 console.log("No duplicate category, submitting form");
        //                 $("#categoryForm").submit();
        //             }
        //         }).catch(function(error) {
        //             console.error("Error occurred during AJAX request:", error);
        //         });
        //     });




        // });

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
                        url: "{{ URL::to('admin/category/check_category_is_exist') }}",
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

//         document.querySelectorAll('.description').forEach(function(textarea) {
//     ClassicEditor
//         .create(textarea)
//         .then(editor => {
//             editor.ui.view.editable.element.style.height = '350px'; // Set the height to 350px or your desired value
//         })
//         .catch(error => {
//             console.error(error);
//         });
// });

document.querySelectorAll('.description').forEach(function(textarea) {
            ClassicEditor
                .create(textarea)
                .then(editor => {
                    // Access the editor's editing view container
                    editor.ui.view.editable.element.style.height = '300px'; // Set the desired height here
                })
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script>
