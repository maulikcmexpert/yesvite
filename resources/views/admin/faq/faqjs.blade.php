<script type="text/javascript">
    var base_url = "{{ url('/') }}/";
    $(function() {

        var table = $("#template_table").DataTable({
            processing: true,
            serverSide: true,


            ajax: '{{ URL::to('/admin/faq') }}',
            columns: [{

                    data: "number",

                    name: "number"

                },

                {

                    data: "question",

                    name: "question"

                },

                {

                    data: "answer",

                    name: "answer"

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

        $(document).on("click", ".delete_faq", function(event) {
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
                                toastr.success("Question Deleted successfully !");
                            } else {
                                toastr.error("Question is not Deleted !");
                            }
                        },
                    });
                }
            });
        });

        document.querySelectorAll('.question').forEach(function(textarea) {
            ClassicEditor
                .create(textarea)
                .then(editor => {
                    // Access the editor's editing view container
                    editor.ui.view.editable.element.style.height =
                    '100px'; // Set the desired height here
                })
                .catch(error => {
                    console.error(error);
                });
        });
        document.querySelectorAll('.answer').forEach(function(textarea) {
            ClassicEditor
                .create(textarea)
                .then(editor => {
                    // Access the editor's editing view container
                    editor.ui.view.editable.element.style.height = '100px';

                })
                .catch(error => {
                    console.error(error);
                });
        });

    });
</script>
