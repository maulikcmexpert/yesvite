<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- jquery-cdn -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<!-- custom-js -->
<script src="{{ asset('assets/admin/js/jquery-validate.js') }}"></script>

<script src="{{ asset('assets/admin/js/jquery-validate-additional.js') }}"></script>
<script src="{{ asset('assets/admin/js/common.js') }}"></script>

@if(isset($js))

@foreach($js as $value)

<script src="{{ asset('assets/front') }}/js/{{$value}}.js"></script>

@endforeach

@endif