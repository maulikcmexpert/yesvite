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



    
        // document.querySelectorAll('.question').forEach(function(textarea) {
        //     ClassicEditor
        //         .create(textarea)
        //         .then(editor => {
        //             // Access the editor's editing view container
        //             editor.ui.view.editable.element.style.height =
        //             '100px'; // Set the desired height here
        //         })
        //         .catch(error => {
        //             console.error(error);
        //         });
        // });
        // document.querySelectorAll('.answer').forEach(function(textarea) {
        //     ClassicEditor
        //         .create(textarea)
        //         .then(editor => {
        //             // Access the editor's editing view container
        //             editor.ui.view.editable.element.style.height = '100px';

        //         })
        //         .catch(error => {
        //             console.error(error);
        //         });
        // });


        // $('#faqAddForm').validate({
        //             rules: {
        //                 question: {
        //                     required: true
        //                 },
        //                 answer: {
        //                     required: true
        //                 }
        //             },
        //             messages: { 
        //                 question: {
        //                     required: "Please enter the question"
        //                 },
        //                 answer: {
        //                     required: "Please enter the answer"
        //                 }
        //             },
                   
        // });

        $(document).ready(function() {
    let questionEditor, answerEditor;

    // Initialize CKEditors for question and answer
    ClassicEditor.create(document.querySelector('#question'))
        .then(editor => {
            questionEditor = editor;
            // Listen for changes in the question editor to remove errors if content is provided
            editor.model.document.on('change:data', function() {
                let questionContent = questionEditor.getData().trim();
                if (questionContent) {
                    $('.err_question').text(''); // Clear error message if there's content
                } else {
                    $('.err_question').text('Please enter a question.'); // Show error if content is empty
                }
            });
        })
        .catch(error => console.error(error));

    ClassicEditor.create(document.querySelector('#answer'))
        .then(editor => {
            answerEditor = editor;
            // Listen for changes in the answer editor to remove errors if content is provided
            editor.model.document.on('change:data', function() {
                let answerContent = answerEditor.getData().trim();
                if (answerContent) {
                    $('.err_answer').text(''); // Clear error message if there's content
                } else {
                    $('.err_answer').text('Please enter an answer.'); // Show error if content is empty
                }
            });
        })
        .catch(error => console.error(error));

    // Form validation on submit
    $('#faqAddForm').on('submit', function(e) {
        let isValid = true;
        let questionContent = questionEditor.getData().trim();
        let answerContent = answerEditor.getData().trim();

        // Clear any previous error messages
        $('.err_question').text('');
        $('.err_answer').text('');

        // Check if the question field is empty
        if (!questionContent) {
            $('.err_question').text('Please enter a question.');
            isValid = false;
        }

        // Check if the answer field is empty
        if (!answerContent) {
            $('.err_answer').text('Please enter an answer.');
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
