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


       

        $(document).on('click', '.delete_faq', function() {
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
                $('#delete_faq_from').submit();

            }
        });
    })



        $(document).ready(function() {
        // Create editors and store references to them for validation
        let questionEditor, answerEditor;

        ClassicEditor.create(document.querySelector('#question'))
            .then(editor => {
                questionEditor = editor;
            })
            // .catch(error => console.error(error));

        ClassicEditor.create(document.querySelector('#answer'))
            .then(editor => {
                answerEditor = editor;
            })
            // .catch(error => console.error(error));

        // Form validation on submit
        $('#faqAddForm').on('submit', function(e) {
            let isValid = true;
            let questionContent = questionEditor.getData().trim();
            let answerContent = answerEditor.getData().trim();

            // Clear previous error messages
            $('.err_question').text('');
            $('.err_answer').text('');

            // Check if question is empty
            if (!questionContent) {
                $('.err_question').text('Question cannot be empty.');
                isValid = false;
            }

            // Check if answer is empty
            if (!answerContent) {
                $('.err_answer').text('Answer cannot be empty.');
                isValid = false;
            }

            // Prevent form submission if validation fails
            if (!isValid) {
                e.preventDefault();
            }
        });
    });

    });
</script>
