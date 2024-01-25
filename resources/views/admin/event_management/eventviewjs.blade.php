<script type="text/javascript">
    $(function() {

        var eventId = $("#event_id").val();

        var table = $("#invitedUsersList").DataTable({
            processing: true,
            serverSide: true,

            ajax: {
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                url: '{{ route("invited_user_data") }}',
                data: function(d) {
                    d.eventId = eventId;
                },
                type: 'post'
            },
            columns: [{
                    data: "number",
                    name: "number"
                },
                {
                    data: "username",
                    name: "username"
                },
                {
                    data: "email",
                    name: "email"
                },
                {
                    data: "rsvp_status",
                    name: "rsvp_status"
                },
                {
                    data: "total_posts",
                    name: "total_posts"
                }


            ],
        });

        $('.owl-carousel').owlCarousel({
            loop: false,
            margin: 10,
            padding: 0,
            nav: false,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 2
                }
            }
        })
    });
</script>