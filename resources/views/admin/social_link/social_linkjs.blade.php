<script type="text/javascript">
    $(function() {
        $("#saveLink_form").validate({
            rules: {
                url: {
                    required: true,
                },
            },
            messages: {
                url: {
                    required: "Please Enter the link",
                },
            }
        })
    });
</script>