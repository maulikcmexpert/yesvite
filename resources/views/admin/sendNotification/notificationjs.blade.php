<script type="text/javascript">
    $(document).ready(function() {
        $('#send_bluk_message').on('click', function(e) {
            e.preventDefault();
            $('.text-danger').text('');
            const formData = {
                title: $('#title').val(),
                message: $('#message').val(),
            };
            if ($('#title').val() != "" && $('#message').val() != "") {
                $('#loader').css('display','flex');
            }
            console.log(formData);
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
                    console.log(response.status);
                   
                    if (response.status == "success") {
                        $('#loader').css('display', 'none');
                        const dashboardUrl = "{{ URL::to('/admin/dashboard') }}";
                        window.location.href = dashboardUrl;
                    }
                    $('#title').val('');
                    $('#message').val('');
                    $('.text-danger').text('');
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
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
