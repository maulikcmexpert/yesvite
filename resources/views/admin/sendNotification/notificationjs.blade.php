<script type="text/javascript">
    $(document).ready(function() {
        $('#send_bluk_message').on('click', function(e) {
            // alert();
            e.preventDefault();

            // Clear previous errors
            $('.text-danger').text('');

            const formData = {
                title: $('#title').val(),
                message: $('#message').val(),
            };
            console.log(formData);
            $('#loader').css('display','none')
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                dataType: 'json',
                type: "POST",
                url: "{{ URL::to('admin/sendNotification/send') }}",
                data: formData,
                success: function(response) {
                    console.log(response);
                    
                    // Handle success (e.g., show a success message)
                    // Optional: Display a success message
                    if(response.status=="success"){
                        alert();
                        $('#loader').css('display','block');
                    }

                    // Clear form fields after successful submission
                    $('#title').val('');
                    $('#message').val('');

                    // Clear any error messages that may have been shown
                    $('.text-danger').text('');
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        // Display validation errors in corresponding fields
                        if (errors.title) {
                            $('#title').next('.text-danger').text(errors.title[0]);
                        }
                        if (errors.message) {
                            $('#message').next('.text-danger').text(errors.message[0]);
                        }
                    }
                }
            });
        });
    });
</script>
