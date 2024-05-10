<script type="text/javascript">
    $(function() {


        var table = $("#design_table").DataTable({
            processing: true,
            serverSide: true,

            ajax: '{{URL::to("/admin/design")}}',
            columns: [{
                    data: "number",
                    name: "number"
                },
                {
                    data: "category_name",
                    name: "category_name"
                },
                {
                    data: "subcategory_name",
                    name: "subcategory_name"
                },

                {
                    data: "design_name",
                    name: "design_name"
                },

                {
                    data: "templete",
                    name: "templete"
                },

                {
                    data: "design_color",
                    name: "design_color"
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
            $(this).parent().remove();
        });


        $("#designForm").validate({
            rules: {
                event_design_category_id: {
                    required: true,

                },
                event_design_subcategory_id: {
                    required: true,

                },
                event_design_style_id: {
                    required: true,
                },
                'event_design_color[]': {
                    required: true,
                    minlength: 1 // Ensures at least one checkbox is checked
                },
                image: {
                    required: true,
                    accept: "image/jpeg, image/png, image"
                },

            },
            messages: {
                event_design_category_id: {
                    required: "Please select design category",

                },
                event_design_subcategory_id: {
                    required: "Please select design subcategory",

                },
                event_design_style_id: {
                    required: "Please select design style",
                },
                'event_design_color[]': "Please select at least one event design color",
                image: {
                    required: "Please upload design image",
                    accept: "Please upload a valid image file (JPEG, PNG)"
                },

            },
        });

        $("#updateDesignForm").validate({
            rules: {
                event_design_category_id: {
                    required: true,

                },
                event_design_subcategory_id: {
                    required: true,

                },
                event_design_style_id: {
                    required: true,
                },
                'event_design_color[]': {
                    required: true,
                    minlength: 1 // Ensures at least one checkbox is checked
                },
                image: {
                    accept: "image/jpeg, image/png, image"
                },


            },
            messages: {
                event_design_category_id: {
                    required: "Please select design category",

                },
                event_design_subcategory_id: {
                    required: "Please select design subcategory",

                },
                event_design_style_id: {
                    required: "Please select design style",
                },
                'event_design_color[]': "Please select at least one event design color",
                image: {

                    accept: "Please upload a valid image file (JPEG, PNG)"
                },

            },

        })

        var selectedcatId = $("#event_design_category_id").find(":selected").val();
        if (selectedcatId != "" || selectedcatId != null) {
            var selectedSubCatId = $("#selectedSubCatId").val();
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                url: "{{URL::to('admin/design/get_selected_subcatdata')}}",
                data: {
                    catId: selectedcatId,
                    subcat: selectedSubCatId
                },
                success: function(output) {
                    $("#event_design_subcategory_id").html(output);
                }

            });
        }

        $(document).on("change", "#event_design_category_id", function() {
            var catId = $(this).val();

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                url: "{{URL::to('admin/design/get_subcatdata')}}",
                data: {
                    catId: catId
                },
                success: function(output) {
                    $("#event_design_subcategory_id").html(output);
                }
            });
        });

    });

    selectImage.onchange = evt => {
        preview = document.getElementById('preview');
        preview.style.display = 'block';
        const [file] = selectImage.files
        if (file) {
            preview.src = URL.createObjectURL(file)
        }
    }

    $(document).on('change','.newcheckbox',function(){
    $(this).parent().removeClass('selected');   
        if ($(this).prop('checked')==true){ 
        $(this).parent().addClass('selected');
    }
  }); 

</script>


