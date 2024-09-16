{{-- <link  href="{{ asset('assets/css/my.css') }}" rel="stylesheet"> --}}

@if(isset($css))

@foreach($css as $value)

<link rel="stylesheet"  href="{{ asset('assets') }}/css/{{$value}}.css"/>

@endforeach

@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
<script src="https://cdn.rawgit.com/naptha/tesseract.js/1.0.10/dist/tesseract.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html-to-image/1.11.11/html-to-image.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">






