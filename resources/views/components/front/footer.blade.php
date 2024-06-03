<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- jquery-cdn -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- owl-carousel-js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!-- custom-js -->
<script src="{{ asset('assets/admin/js/jquery-validate.js') }}"></script>

<script src="{{ asset('assets/admin/js/jquery-validate-additional.js') }}"></script>
<script src="{{ asset('assets/front/js/common.js') }}"></script>



<script src="{{ asset('assets/front/js/script.js') }}"></script>

<!-- ======== wow-js ======== -->
<script src="{{ asset('assets/front/js/animation.js') }}"></script>
<script src="{{ asset('assets/front/js/wow.min.js') }}"></script>

<script>
    new WOW().init()
</script>
@if(isset($js))

@foreach($js as $value)

<script src="{{ asset('assets/front') }}/js/{{$value}}.js"></script>

@endforeach

@endif