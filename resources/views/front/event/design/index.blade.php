


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Templates</title>

    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            text-align: center;
        }

        h1 {
            margin-top: 20px;
        }

        .carousel {
            width: 80%;
            margin: 0 auto;
        }

        .template {
            cursor: pointer;
            padding: 10px;
        }

        .template img {
            width: 100%;
            height: auto;
            max-height: 600px; /* Set max height for images */
            border: 2px solid black;
            border-radius: 10px;
            object-fit: cover; /* Maintain aspect ratio and cover the area */
        }

        .template p {
            margin-top: 5px;
            font-size: 18px;
            color: #333;
        }

        /* Responsive Styles for Slick Carousel */
        @media (max-width: 1200px) {
            .carousel .slick-slide {
                padding: 0 10px; /* Adjust spacing for smaller screens */
            }
        }

        @media (max-width: 768px) {
            .carousel .slick-slide {
                padding: 0 5px; /* Adjust spacing for extra small screens */
            }
        }
    </style>
</head>
<body>
    <div class="clear-fix" style="margin-top:30px;">

        <a class="btn btn-primary" href="{{ route('template.data')}}" style="float:right;">Show</a>

    </div>
    <h1>Select a Template</h1>
    <div class="carousel">
        <div class="template" name="post_temp_1">
            <img src="{{ asset('Templates/post_temp_1.PNG') }}" alt="Birthday Template">
            <p>Template-1</p>
            <button type="button" class="btn btn-success save-button">Save</button>
            <button type="button" class="btn btn-primary edit-button">Edit</button>
        </div>

        <div class="template" name="post_temp_2">
            <img src="{{ asset('Templates/post_temp_2.PNG') }}" alt="Bechoral Party Template">
            <p>Template-2</p>
            <button type="button" class="btn btn-success save-button">Save</button>
            <button type="button" class="btn btn-primary edit-button">Edit</button>
        </div>

        <div class="template" name="post_temp_3">
            <img src="{{ asset('Templates/post_temp_3.PNG') }}" alt="Wedding Template">
            <p>Template-3</p>
            <button type="button" class="btn btn-success save-button">Save</button>
            <button type="button" class="btn btn-primary edit-button">Edit</button>
        </div>

        <div class="template" name="post_temp_4">
            <img src="{{ asset('Templates/post_temp_4.PNG') }}" alt="Party Template">
            <p>post_temp_4</p>
            <button type="button" class="btn btn-success save-button">Save</button>
            <button type="button" class="btn btn-primary edit-button">Edit</button>
        </div>

        <div class="template" name="post_temp_5">
            <img src="{{ asset('Templates/post_temp_5.PNG') }}" alt="Party Template">
            <p>post_temp_5</p>
            <button type="button" class="btn btn-success save-button">Save</button>
            <button type="button" class="btn btn-primary edit-button">Edit</button>
        </div>

        <div class="template" name="post_temp_6">
            <img src="{{ asset('Templates/post_temp_6.PNG') }}" alt="Party Template">
            <p>post_temp_6</p>
            <button type="button" class="btn btn-success save-button">Save</button>
            <button type="button" class="btn btn-primary edit-button">Edit</button>
        </div>

        <div class="template" name="post_temp_7">
            <img src="{{ asset('Templates/post_temp_7.PNG') }}" alt="Party Template">
            <p>post_temp_7</p>
            <button type="button" class="btn btn-success save-button">Save</button>
            <button type="button" class="btn btn-primary edit-button">Edit</button>
        </div>

        <div class="template" name="post_temp_8">
            <img src="{{ asset('Templates/post_temp_8.PNG') }}" alt="Party Template">
            <p>post_temp_8</p>
            <button type="button" class="btn btn-success save-button">Save</button>
            <button type="button" class="btn btn-primary edit-button">Edit</button>
        </div>

        <div class="template" name="post_temp_9">
            <img src="{{ asset('Templates/post_temp_9.PNG') }}" alt="Party Template">
            <p>post_temp_9</p>
            <button type="button" class="btn btn-success save-button">Save</button>
            <button type="button" class="btn btn-primary edit-button">Edit</button>
        </div>

        <div class="template" name="post_temp_10">
            <img src="{{ asset('Templates/post_temp_10.PNG') }}" alt="Party Template">
            <p>post_temp_10</p>
            <button type="button" class="btn btn-success save-button">Save</button>
            <button type="button" class="btn btn-primary edit-button">Edit</button>
        </div>



        <div class="template" name="post_temp_11">
            <img src="{{ asset('Templates/post_temp_11.PNG') }}" alt="Engagement Template">
            <p>Template-11</p>
            <button type="button" class="btn btn-success save-button">Save</button>
            <button type="button" class="btn btn-primary edit-button">Edit</button>
        </div>




    </div>


    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Slick Carousel JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <!-- Slick Carousel Initialization -->
    {{-- <script>
        $(document).ready(function(){
            $('.carousel').slick({
                slidesToShow: 3, // Display 3 images by default
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000, // Adjust speed as needed
                arrows: true, // Show navigation arrows
                dots: true, // Show navigation dots
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2 // Show 2 images on small screens
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1 // Show 1 image on extra small screens
                        }
                    }
                ]
            });

//             $('.template').click(function() {
//     var templateName = $(this).attr('name');
//     var url = '/template/' + templateName;

//     $.ajax({
//         url: url,
//         type: 'GET',
//         success: function(response) {
//             $('#content-container').html(response);
//         },
//         error: function(xhr, status, error) {
//             console.error('AJAX Error:', status, error);
//         }
//     });
// });

$('.template').click(function() {
    var templateName = $(this).attr('name');
    var url = '/template/'+templateName;
    window.location.href = url;
});
        });

    </script> --}}

    <script>
        $(document).ready(function () {
            // Initialize Slick Carousel
            $('.carousel').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                arrows: true,
                dots: true,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });


            $('.carousel').on('click', '.edit-button', function () {
                var $template = $(this).closest('.template');
                var templateName = $template.attr('name');


                var url = '/template/'+templateName;
                window.location.href = url;

            });


            $('.carousel').on('click', '.save-button', function () {
            var $template = $(this).closest('.template');
            var imageSrc = $template.find('img').attr('src');
            var imageName = $template.find('p').text().trim();


            var link = document.createElement('a');
            link.href = imageSrc;
            link.download = imageName + '.png';
            link.style.display = 'none';


            document.body.appendChild(link);


            link.click();


            document.body.removeChild(link);
        });
        });
    </script>

</body>
</html>

