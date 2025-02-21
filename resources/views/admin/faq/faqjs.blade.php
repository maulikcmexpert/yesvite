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
            var id=$(this).data('id');

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
                $('#delete_faq_from'+id).submit();

            }
        });
    })




    $(document).ready(function() {
    let questionEditor, answerEditor;

    // Initialize CKEditors for question and answer
    ClassicEditor.create(document.querySelector('#question'))
        .then(editor => {
            questionEditor = editor;
            editor.model.document.on('change:data', function() {
                let questionContent = questionEditor.getData().trim();
                if (questionContent.length === 0) {
                    $('.err_question').text('Please enter a question.');
                } else {
                    $('.err_question').text('');
                }
            });
        })
        .catch(error => console.error(error));

    ClassicEditor.create(document.querySelector('#answer'))
        .then(editor => {
            answerEditor = editor;
            editor.model.document.on('change:data', function() {
                let answerContent = answerEditor.getData().trim();
                if (answerContent.length === 0) {
                    $('.err_answer').text('Please enter an answer.');
                } else {
                    $('.err_answer').text('');
                }
            });
        })
        .catch(error => console.error(error));

    $('#faqAddForm').on('submit', function(e) {
        let isValid = true;
        let questionContent = questionEditor.getData().trim();
        let answerContent = answerEditor.getData().trim();

        $('.err_question').text('');
        $('.err_answer').text('');

        let wordCountQuestion = questionContent.split(/\s+/).filter(word => word.length > 0).length;
        let wordCountAnswer = answerContent.split(/\s+/).filter(word => word.length > 0).length;

        if (wordCountQuestion < 2) {
            $('.err_question').text('Please enter at least two words.');
            isValid = false;
        }
        if (wordCountAnswer < 2) {
            $('.err_answer').text('Please enter at least two words.');
            isValid = false;
        }
        if (!isValid) {
            e.preventDefault();
        }
    });
});

    });
</script>
